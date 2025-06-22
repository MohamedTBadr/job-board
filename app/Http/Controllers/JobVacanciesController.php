<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyJobRequest;
use Illuminate\Http\Request;
use App\Models\JobVacancy;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\Resume;
use App\Models\JobApplication;
use App\Services\ResumeAnalysisService;


class JobVacanciesController extends Controller
{
    //
    protected $resumeAnalysisService;

    public function __construct(ResumeAnalysisService $resumeAnalysisService)
    {
        $this->resumeAnalysisService = $resumeAnalysisService;
    }

    public function show($id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        return view("job-vacancies.show", compact("jobVacancy"));
    }

    public function apply($id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $resumes = auth()->user()->resumes;
        return view('job-vacancies.apply', compact('jobVacancy', 'resumes'));
    }

    public function processApplication(ApplyJobRequest $request, string $id)
    {
        $jobVacancy= JobVacancy::findOrFail($id);
        $extractedInfo=null;
        $resumeId=null;
        if ($request->input('resume_option') == 'new_resume') {
            $file = $request->file('resume_file');
            $extension = $file->getClientOriginalExtension();
            $orignalFileName = $file->getClientOriginalName();
            //unique for avoiding overriding
            $fileName = 'resume_' . time() . $extension;

            //storing

            $path = $file->storeAs('resumes', $fileName, 'cloud');
            $fileUrl = config('filesystem.disks.cloud.url') . '/' . $path;

            //TODO
            $extractedInfo=$this->resumeAnalysisService->extractResumeInfromation($fileUrl);

         

            $resume = Resume::create([
                'file_name' => $orignalFileName,
                'fileUrl' => $path,
                'user_id' => auth()->id(),
                'Contact_details' => json_encode([
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ]),
                'summary' => $extractedInfo['summary'],
                'education' => $extractedInfo['education'],
                'skills' => $extractedInfo['skills'],
                'experience' => $extractedInfo['experience']
            ]);
            $resumeId=$resume->id;


        } else {
            $resumeId=$request->input('resume_option');
            $resume= Resume::findOrFail($resumeId);
              $extractedInfo=[      'summary' => '',
                'education' => '',
                'skills' => '',
                'experience' => ''];

        }
           //TODO Evaluate Job App
            $evalution=$this->resumeAnalysisService->analyzeResume($jobVacancy,$extractedInfo);

           
            JobApplication::create([
                'status' => 'Pending',
                'JobVacancy_id' => $id,
                'resume_id' => $resumeId,
                'user_id' => auth()->id(),
                'AI_Generated_score' => $evalution['AI_Generated_score'],
                'AI_Generated_feedback' =>$evalution['AI_Generated_feedback']

            ]);
        return redirect()->route('job-applications.index', $id)->with('success', 'Applied succesfully');
    }
}
