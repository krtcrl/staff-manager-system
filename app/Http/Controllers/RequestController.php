<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\RequestModel;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;
use App\Events\NewRequestCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PartProcess;
use App\Models\Process;
use App\Services\RequestService; 
use PhpOffice\PhpSpreadsheet\IOFactory;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RequestController extends Controller
{
    protected $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'unique_code' => 'required|string|max:255',
                'part_number' => 'required|string|max:255',
                'part_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'attachment' => 'nullable|file|mimes:xls,xlsx,xlsb|max:2048',
                'final_approval_attachment' => 'nullable|file|mimes:xls,xlsx,xlsb|max:2048',
            ]);
    
            // Use transaction for consistency
            return DB::transaction(function () use ($validatedData, $request) {
                // Fetch processes for the selected part number
                $processes = DB::table('part_processes')
                    ->where('part_number', $validatedData['part_number'])
                    ->orderBy('process_order')
                    ->get();
    
                if ($processes->isEmpty()) {
                    Log::error('No processes found for part number:', ['part_number' => $validatedData['part_number']]);
                    return response()->json(['error' => 'No processes found for the selected part number.'], 400);
                }
    
                // Count total processes based on process_order
                $totalProcesses = DB::table('part_processes')
                    ->where('part_number', $validatedData['part_number'])
                    ->count();
    
                // Set process-related fields
                $validatedData['process_type'] = $processes->first()->process_type;
                $validatedData['current_process_index'] = 1;
                $validatedData['total_processes'] = $totalProcesses;
    
                // Handle file uploads
                if ($request->hasFile('attachment')) {
                    Log::info('Attachment file detected:', ['file' => $request->file('attachment')]);
                    try {
                        $attachmentPath = $this->handleFileUpload($request->file('attachment'), 'attachments');
                        Log::info('Attachment file stored at:', ['path' => $attachmentPath]);
                        $validatedData['attachment'] = $attachmentPath;
                    } catch (\Exception $e) {
                        Log::error('Error uploading attachment file:', ['error' => $e->getMessage()]);
                        return response()->json(['error' => 'Failed to upload attachment file.'], 500);
                    }
                }
    
                if ($request->hasFile('final_approval_attachment')) {
                    Log::info('Final approval attachment file detected:', ['file' => $request->file('final_approval_attachment')]);
                    try {
                        $finalApprovalPath = $this->handleFileUpload($request->file('final_approval_attachment'), 'final_approval_attachments');
                        Log::info('Final approval attachment file stored at:', ['path' => $finalApprovalPath]);
                        $validatedData['final_approval_attachment'] = $finalApprovalPath;
                    } catch (\Exception $e) {
                        Log::error('Error uploading final approval attachment file:', ['error' => $e->getMessage()]);
                        return response()->json(['error' => 'Failed to upload final approval attachment file.'], 500);
                    }
                }
    
                // Add staff_id
                $validatedData['staff_id'] = Auth::guard('staff')->id();
    
                // Insert into database
                Log::info('Inserting request into database:', ['data' => $validatedData]);
                try {
                    $requestModel = RequestModel::create($validatedData);
    
                    if ($requestModel) {
                        Log::info('Request inserted successfully:', ['request' => $requestModel]);
                        DB::afterCommit(function () use ($requestModel) {
                            broadcast(new NewRequestCreated($requestModel))->toOthers();
                        });
    
                        return response()->json(['success' => 'Request submitted successfully!', 'request' => $requestModel]);
                    } else {
                        Log::error('Failed to insert request into database.');
                        return response()->json(['error' => 'Failed to submit request.'], 500);
                    }
                } catch (\Exception $e) {
                    Log::error('Error inserting request into database:', ['error' => $e->getMessage()]);
                    return response()->json(['error' => 'An error occurred while inserting the request.'], 500);
                }
            });
    
        } catch (\Exception $e) {
            Log::error('Error in store method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'An error occurred while submitting the request.'], 500);
        }
    }

private function handleFileUpload($file, $directory)
{
    $extension = $file->getClientOriginalExtension();
    Log::info('Handling file upload:', ['file' => $file, 'directory' => $directory]);

    // Store the original file
    $originalPath = $file->store($directory, 'public');
    Log::info('File stored at:', ['path' => $originalPath]);

    // Convert Excel to PDF if needed
    if (in_array($extension, ['xls', 'xlsx', 'xlsb'])) {
        $excelPath = storage_path("app/public/$originalPath");
        Log::info('Excel file detected, converting to PDF:', ['excelPath' => $excelPath]);

        $pdfPath = $this->convertExcelToPdf($excelPath);

        if ($pdfPath) {
            Log::info('PDF file generated at:', ['pdfPath' => $pdfPath]);
            // Delete the original Excel file
            Storage::disk('public')->delete($originalPath);
            Log::info('Original Excel file deleted:', ['path' => $originalPath]);
            return $pdfPath;
        } else {
            Log::error('PDF conversion failed, returning original Excel file.');
            return $originalPath; // Return the original Excel file if PDF conversion fails
        }
    }

    // Return the original file path if not Excel
    Log::info('File is not Excel, returning original path:', ['path' => $originalPath]);
    return $originalPath;
}

private function convertExcelToPdf($excelPath)
{
    try {
        $spreadsheet = IOFactory::load($excelPath);
        $mpdf = new Mpdf();

        // Loop through each sheet and add it to the PDF
        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $htmlWriter = IOFactory::createWriter($spreadsheet, 'Html');
            $htmlWriter->setSheetIndex($spreadsheet->getIndex($sheet));

            // Save HTML content temporarily
            $htmlPath = storage_path('app/public/temp_' . $sheetName . '.html');
            $htmlWriter->save($htmlPath);

            // Add HTML content to PDF
            $mpdf->WriteHTML(file_get_contents($htmlPath));
            $mpdf->AddPage(); // Add a new page for the next sheet

            // Delete temporary HTML file
            unlink($htmlPath);
        }

        // Save the combined PDF
        $pdfPath = 'attachments/' . pathinfo($excelPath, PATHINFO_FILENAME) . '.pdf';
        $mpdf->Output(storage_path("app/public/$pdfPath"), \Mpdf\Output\Destination::FILE);

        return $pdfPath;

    } catch (\Exception $e) {
        Log::error('Excel to PDF conversion failed: ' . $e->getMessage());
        return null; // Return null if conversion fails
    }
}

    private function handleMergedCells($sheet, $htmlContent)
    {
        // Get all merged cells in the sheet
        $mergedCells = $sheet->getMergeCells();

        foreach ($mergedCells as $mergedCellRange) {
            // Extract the top-left cell of the merged range
            $topLeftCell = explode(':', $mergedCellRange)[0];

            // Get the value of the top-left cell
            $cellValue = $sheet->getCell($topLeftCell)->getValue();

            // Replace the merged cells in the HTML content with the value of the top-left cell
            $htmlContent = preg_replace(
                '/<td[^>]*>' . preg_quote($cellValue, '/') . '<\/td>/',
                '<td colspan="' . $this->getColspan($mergedCellRange) . '" rowspan="' . $this->getRowspan($mergedCellRange) . '">' . $cellValue . '</td>',
                $htmlContent
            );
        }

        return $htmlContent;
    }

    private function getColspan($mergedCellRange)
    {
        $cells = explode(':', $mergedCellRange);
        $startCell = $cells[0];
        $endCell = $cells[1];

        $startCol = preg_replace('/[^A-Z]/', '', $startCell);
        $endCol = preg_replace('/[^A-Z]/', '', $endCell);

        return ord($endCol) - ord($startCol) + 1;
    }

    private function getRowspan($mergedCellRange)
    {
        $cells = explode(':', $mergedCellRange);
        $startCell = $cells[0];
        $endCell = $cells[1];

        $startRow = preg_replace('/[^0-9]/', '', $startCell);
        $endRow = preg_replace('/[^0-9]/', '', $endCell);

        return $endRow - $startRow + 1;
    }

    public function show($id)
    {
        // Fetch the request
        $request = RequestModel::findOrFail($id);
    
        // Initialize variables for Excel sheets and PDF URL
        $excelSheets = [];
        $pdfUrl = null;
    
        // Handle Excel files
        if ($request->attachment && in_array(pathinfo($request->attachment, PATHINFO_EXTENSION), ['xls', 'xlsx', 'xlsb'])) {
            $filePath = storage_path('app/public/' . $request->attachment);
    
            try {
                // Load the Excel file
                $spreadsheet = IOFactory::load($filePath);
    
                // Load all sheets, regardless of whether they have data or not
                foreach ($spreadsheet->getSheetNames() as $sheetName) {
                    $sheet = $spreadsheet->getSheetByName($sheetName);
                    $sheetData = $sheet->toArray(null, true, true, true); // Read all cells, including merged cells
    
                    // Add the sheet data to the array
                    $excelSheets[$sheetName] = $sheetData;
                }
            } catch (\Exception $e) {
                Log::error('Error loading Excel file:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return back()->with('error', 'Failed to load the Excel file. Please check the file format.');
            }
    
            // Convert Excel to PDF if needed (optional, can be removed if not required)
            try {
                $pdfUrl = $this->convertExcelToPdf($filePath);
            } catch (\Exception $e) {
                Log::error('Excel to PDF conversion failed:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                // If PDF conversion fails, continue without it
            }
        }
    
        // Handle PDF files
        if ($request->attachment && pathinfo($request->attachment, PATHINFO_EXTENSION) === 'pdf') {
            $pdfUrl = asset('storage/' . $request->attachment);
        }
    
        return view('staff.requests.show', [
            'request' => $request,
            'excelSheets' => $excelSheets, // Pass all sheets, including empty ones
            'pdfUrl' => $pdfUrl,
        ]);
    }

    public function destroy($id)
    {
        try {
            $requestModel = RequestModel::find($id);

            if (!$requestModel) {
                return redirect()->route('staff.dashboard')->with('error', 'Request not found.');
            }

            // Delete the request
            $requestModel->delete();

            return redirect()->route('staff.dashboard')->with('success', 'Request deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Error in destroy method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('staff.dashboard')->with('error', 'Failed to delete request.');
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Update method called for request ID: ' . $id);

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'unique_code' => 'required|string|max:255',
                'part_number' => 'required|string|max:255',
                'part_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
                'attachment' => 'nullable|file|mimes:xls,xlsx,xlsb|max:2048',
                'final_approval_attachment' => 'nullable|file|mimes:xls,xlsx,xlsb|max:2048',
            ]);

            $requestModel = RequestModel::findOrFail($id);

            // Clear description if request was rejected
            if ($requestModel->status === 'rejected') {
                $validatedData['description'] = null;
                Log::info('Description cleared due to rejection');
            }

            // Handle attachment removal
            if ($request->has('remove_attachment')) {
                Log::info('Removing main attachment');

                if ($requestModel->attachment && Storage::disk('public')->exists($requestModel->attachment)) {
                    Storage::disk('public')->delete($requestModel->attachment);
                }
                $validatedData['attachment'] = null;
            }

            // Handle final approval attachment removal
            if ($request->has('remove_final_approval_attachment')) {
                Log::info('Removing final approval attachment');

                if ($requestModel->final_approval_attachment && Storage::disk('public')->exists($requestModel->final_approval_attachment)) {
                    Storage::disk('public')->delete($requestModel->final_approval_attachment);
                }
                $validatedData['final_approval_attachment'] = null;
            }

            // Handle new main attachment upload
            if ($request->hasFile('attachment')) {
                Log::info('New main attachment uploaded');

                // Delete old attachment if it exists
                if ($requestModel->attachment && Storage::disk('public')->exists($requestModel->attachment)) {
                    Storage::disk('public')->delete($requestModel->attachment);
                }

                // Store new attachment
                $attachmentPath = $this->handleFileUpload($request->file('attachment'), 'attachments');
                Log::info('Main attachment stored at:', ['path' => $attachmentPath]);
                $validatedData['attachment'] = $attachmentPath;
            }

            // Handle new final approval attachment upload
            if ($request->hasFile('final_approval_attachment')) {
                Log::info('New final approval attachment uploaded');

                // Delete old final approval attachment if it exists
                if ($requestModel->final_approval_attachment && Storage::disk('public')->exists($requestModel->final_approval_attachment)) {
                    Storage::disk('public')->delete($requestModel->final_approval_attachment);
                }

                // Store new final approval attachment
                $finalApprovalPath = $this->handleFileUpload($request->file('final_approval_attachment'), 'final_approval_attachments');
                Log::info('Final approval attachment stored at:', ['path' => $finalApprovalPath]);
                $validatedData['final_approval_attachment'] = $finalApprovalPath;
            }

            // Update the request with the new data
            $requestModel->update($validatedData);

            // Move request to final requests if completed
            $this->requestService->moveCompletedRequestToFinal($requestModel->id);

            Log::info('Request updated successfully');

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Error updating request:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}