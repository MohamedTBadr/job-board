<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'resume_option'=>'required|string',
            'resume_file'=>'requiredif:resume_option,new_resume|file|mimes:pdf|max:5120',
        ];
    }
    public function messages()
    {
        return [
            'resume_file.required'=> 'Resume File is Required',
            'resume_file.file'=> 'Resume File must be file',

        ];
    }
}
