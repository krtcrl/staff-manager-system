<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinalRequestController extends Controller
{
    /**
     * Display the Final Request List.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('manager.finalrequest_list');
    }
    
}