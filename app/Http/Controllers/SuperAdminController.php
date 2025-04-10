<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff; // Assuming you have a Staff model

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:superadmin');
    }

    // ✅ Dashboard View
    public function dashboard()
    {
        return view('superadmin.superadmin_main');
    }

    // ✅ Display All Staff
    public function index()
    {
        
        $staff = Staff::paginate(10); // 10 items per page
        return view('superadmin.staff_table', compact('staff'));
    }

    // ✅ Staff Table (Alternate method for display)
    public function staffTable()
    {
        $staff = Staff::all();
        return view('superadmin.staff_table', compact('staff'));
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Staff member deleted successfully'
        ]);
    }
    

    // ✅ Edit Staff Member Form
    public function edit($id)
    {
        $staff = Staff::findOrFail($id); // Find the staff member by ID
        return view('superadmin.staff_edit', compact('staff'));
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:staff,email,' . $id,
        ]);
    
        $staff = Staff::findOrFail($id);
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Staff member updated successfully'
        ]);
    }
    
    

    // ✅ Logout
    public function logout()
    {
        Auth::guard('superadmin')->logout();
        return redirect()->route('superadmin.login');  // Change this to your login route
    }

}
