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
                    $attachmentPath = $this->handleFileUpload($request->file('attachment'), 'attachments');
                    $validatedData['attachment'] = $attachmentPath;
                }

                if ($request->hasFile('final_approval_attachment')) {
                    $finalApprovalPath = $this->handleFileUpload($request->file('final_approval_attachment'), 'final_approval_attachments');
                    $validatedData['final_approval_attachment'] = $finalApprovalPath;
                }

                // Add staff_id
                $validatedData['staff_id'] = Auth::guard('staff')->id();

                // Insert into database
                $requestModel = RequestModel::create($validatedData);

                if ($requestModel) {
                    DB::afterCommit(function () use ($requestModel) {
                        broadcast(new NewRequestCreated($requestModel))->toOthers();
                    });

                    return response()->json(['success' => 'Request submitted successfully!', 'request' => $requestModel]);
                } else {
                    return response()->json(['error' => 'Failed to submit request.'], 500);
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

        // Store the original file
        $originalPath = $file->store($directory, 'public');

        // Convert Excel to PDF if needed
        if (in_array($extension, ['xls', 'xlsx', 'xlsb'])) {
            $excelPath = storage_path("app/public/$originalPath");
            $pdfPath = $this->convertExcelToPdf($excelPath);

            // Delete the original Excel file
            Storage::disk('public')->delete($originalPath);

            return $pdfPath;
        }

        // Return the original file path if not Excel
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
            return null;
        }
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
        $spreadsheet = IOFactory::load($filePath);

        // Filter out empty sheets
        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $sheetData = $sheet->toArray();

            // Check if the sheet has any data (non-empty cells)
            $hasData = false;
            foreach ($sheetData as $row) {
                foreach ($row as $cell) {
                    if (!empty($cell)) {
                        $hasData = true;
                        break 2; // Exit both loops if data is found
                    }
                }
            }

            // Only include sheets with data
            if ($hasData) {
                $excelSheets[$sheetName] = $sheetData;
            }
        }

        // Convert Excel to PDF if needed
        $pdfUrl = $this->convertExcelToPdf($filePath);
    }

    // Handle PDF files
    if ($request->attachment && pathinfo($request->attachment, PATHINFO_EXTENSION) === 'pdf') {
        $pdfUrl = asset('storage/' . $request->attachment);
    }

    return view('staff.requests.show', [
        'request' => $request,
        'excelSheets' => $excelSheets, // Pass only sheets with data
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
    public function convertPdfToExcel(Request $request)
    {
        $fileUrl = $request->input('fileUrl');
        $filePath = storage_path('app/public/' . basename($fileUrl));
    
        // Load the PDF file (you may need a library like `smalot/pdfparser` to parse PDFs)
        // For now, this is a placeholder. You need to implement PDF parsing logic.
        $pdfContent = file_get_contents($filePath);
    
        // Create a new Excel file
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Add data to the Excel sheet (replace this with actual PDF parsing logic)
        $sheet->setCellValue('A1', 'PDF Content');
        $sheet->setCellValue('A2', $pdfContent);
    
        // Save the Excel file
        $writer = new Xlsx($spreadsheet);
        $excelFilePath = storage_path('app/public/' . basename($fileUrl, '.pdf') . '.xlsx');
        $writer->save($excelFilePath);
    
        // Return the Excel file as a download response
        return response()->download($excelFilePath)->deleteFileAfterSend(true);
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