<?php


namespace App\Services;


use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class ResumeAnalysisService {



    public function extractResumeInfromation(string $fileUrl){
        try{
        //Extract raw text from Resume,read pdf and get text
        $rawText=$this->extractTextFromPdf($fileUrl);

        Log::debug('Successfully Extracted File'.strlen($rawText));
        //Use OpenAI API to organize text into structured format
        $response=OpenAI::chat()->create([
            'model'=>'gpt-4o',
            'messages'=>[
                'role'=>'system',
                'content'=>'Extract information exactly as it appear in resume without adding any interpreatation or additional info, the output should be in  json format'
            ],[
                'role'=>'user',
                'content'=>"parse the following resume content and extract the information as json object with exact keys :'summary','skills','experience','education',the resume content {$rawText}.return an empty string for key that if not found "
            ],'respone_format'=>[
                'type'=>'json_object'
            ],
            'temperature'=>0
        ]);
        $result=$response->choices[0]->message->content;
        Log::debug('OpenAI response'.$result);

        $parsedResult=json_decode($result,true);

        $requiredKeys=['summary','skills','experience','education'];
        $missingKeys=array_diff($requiredKeys,array_keys($parsedResult));

        if(count($missingKeys)> 0){

            Log::debug(''.count($missingKeys));
            throw new \Exception('Missing required keys');

        }

        //return json Object
        return [
            'summary'=>$parsedResult['summary']??'',
            'skills'=>$parsedResult['skills']??'',
            'experience'=>$parsedResult['experience']??'',
            'education'=>$parsedResult['education']??''

        ];
    }catch(\Exception $e){

        Log::error('Error extracting resume info'.$e->getMessage());
        return [
 'summary'=>'',
            'skills'=>'',
            'experience'=>'',
            'education'=>''            
        ];
    }
    }

    public function analyzeResume($jobVacancy,$resumeData){
        try{
            $jobDetails=json_encode([
               'job_title'=>$jobVacancy->title,
                'job_description'=>$jobVacancy->description,
                'job_location'=> $jobVacancy->location,
                'job_type'=>$jobVacancy->type,
                'job_salary'=> $jobVacancy->salary,
                ]);

                $resumeDetails=json_encode($resumeData);

                $response=OpenAI::chat()->create([
                    'model'=>'gpt-4o',
                    'messages'=>[
                        'role'=>'system',
                        'content'=>"you are an expert HR professional and Job recruiter,
                        analyze resumes and determine if candidate is good for job,output in json format provide score from 0 to 100 for candidate suistabiliy for job and specific deatiled feedback
                        response should only json that has following keys:'AI_Generated_score','AI_Generated_feedback'.
                        AI feedback should be detailed and specific"
                    ],
                    [
                        'role'=>'user',
                        'content'=>"Please evalute Job Application .job Details {$jobDetails} ,Resume Details {$resumeDetails}" 
                    ],
                    'response_fromat'=>[
                        'type'=>'jsob_object'
                    ],
                    'temperature'=>0
                    ]);

                    $result=$response->choices[0]->message->content;
                    Log::debug('OpenAI Response'.$result);
                    $parsedResult=json_decode($result,true);

                    return [
                        'AI_Generated_score'=>$parsedResult['AI_Generated_score']??''
                        ,'AI_Generated_feedback'=>$parsedResult['AI_Generated_feedback']??''
                    ];
        }catch(\Exception $e){
                      return [
                        'AI_Generated_score'=>0
                        ,'AI_Generated_feedback'=>'An Error Occured While Analyzing Resume,try again later'
                    ];
        }
    }
    private function extractTextFromPdf(string $fileUrl):string 
    {
        
        #region File Streaming and file open From Cloud to local storage
        $tempFile=tempnam(sys_get_temp_dir(),'resume');
        $filePath=parse_url($fileUrl,PHP_URL_PATH);

        if(!$filePath){
            throw new \Exception("Invalid File Url");
        }

        $fileName=basename($filePath);

        $storagePath="resumes/{$fileName}";

        if(!Storage::disk('cloud')->exists($storagePath)){
            throw new \Exception("File not found");

        }


        $pdfContent=Storage::disk('cloud')->get($storagePath);
        
        if(!$pdfContent){
            throw new \Exception('Failed to read file');
        }


        file_put_contents($tempFile,$pdfContent);
        

        //Check if pdf to text installed

        $pdfToTextPath=['/usr/bin/pdftotext'];
        $pdfToTextAvailable=false;
        foreach($pdfToTextPath as $path){
            if(file_exists($path)){
                $pdfToTextAvailable=true;
                break;   
            }
        }

        if(!$pdfToTextAvailable){
            throw new \Exception('pdf to text is not installed');
        }


        #endregion
        //Extracted Text 
        $text=(new Pdf())->setPdf($tempFile)->text();

        unlink($tempFile);

        return $text;
    }

}

