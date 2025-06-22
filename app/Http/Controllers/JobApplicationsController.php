<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;


class JobApplicationsController extends Controller
{
    //
    public function index(){
        $jobApplications = JobApplication::where('user_id', auth()->user()->id)
            ->latest()
            ->paginate(10);
        return view('job-applications.index',compact('jobApplications'));
    }
}
