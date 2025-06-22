<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;

class JobApplicationsController extends Controller
{
    //
    public function index(){
        $jobApplications=JobApplication::where('user_id',auth()->user()->id()->get())
        ->latest()->paginate(10);
        return view('job-application.index',compact('jobApplications'));
    }
}
