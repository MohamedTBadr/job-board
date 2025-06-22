<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{$jobVacancy->title}} - Apply
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-black shadow-lg rounded-lg p-6 max-w-7xl mx-auto">
            <a href="{{ route('job-vacancies.show',$jobVacancy->id) }}" class="text-blue-500 hover:underline mb-6 inline-block"> ← Back To Job Details</a>

            <div class="border-b border-white/10 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $jobVacancy->title }}</h1>
                        <p class="text-md text-gray-400">{{ $jobVacancy->company->name }}</p>

                        <div class="flex items-center gap-2">
                            <p class="text-gray-400">{{ $jobVacancy->location }}</p>
                            <span>*</span>
                            <p class="text-gray-400">${{ number_format($jobVacancy->salary) }}</p>
                            <span>*</span>
                            <span class="bg-indigo-500 text-white p-2 rounded-lg">{{ $jobVacancy->type }}</span>


                        </div>

                    </div>

                </div>
                @if($errors->any())
                <div class="bg-red-500 text-white m-3 p-4 rounded-lg">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                @endif
                <form enctype="multipart/form-data"  action="{{ route('job-vacancies.process-application',$jobVacancy->id) }}" method="POST">
                    @csrf

                    <div>
                        <h3 class="text-xl font-semibold text-white mb-4">Choose Your Resume</h3>

                        <div class="mb-6">
                            <x-input-label for="resume" value="Select From Your Resumes" />
                            <div class="space-y-4">
                                @forelse($resumes as $resume)


                                <div class="flex items-center gap-2">
                                    <input type="radio" name="resume_option" id="{{ $resume->id }}" value="{{ $resume->id }}"
                                    @error('resume_option') class="border-red-500" @else class="border-gray-600" @enderror>
                                    <label for="{{ $resume->id }}">{{ $resume->file_name }} <span>
                                     (Last Update   {{ $resume->updated_at->format('M d,Y') }})
                                    </span> </label>
                                </div>
                                @empty
                                <span>No Resume Found</span>
                                @endforelse

                            </div>
                        </div>

                    </div>
                    <div>
                        <div x-data="{fileName:''}">

                      <div class="flex items-center">

                          <input x-ref="newResumeRadio" type="radio" name="resume_option" id="new_resume" value="new_resume"
                          @error('resume_option') class="border-red-500 mr-2" @else class="border-gray-600 mr-2" @enderror>
                          <x-input-label for="new_resume" value="Upload new Resume"/>
                        </div>
                         
                        <div class="flex items-center">
                            <div class="flex-1">
                                <label for="resume_file" class="block text-white cursor-pointer">
                                    <div class="border-2 border-dashed border-gray-600 rounded-lg p-4 hover:border-blue-500 transition"
                                     :class="{'border-blue-500':fileName,'border-red-500':{{ $errors->has('resume_file')?'true' :'false' }}}">
                                        <input type="file" name="resume_file" id="resume_file" @change="fileName=$event.target.files[0].name; $refs.newResumeRadio.checked=true" class="hidden" accept= ".pdf">
                                        <div class="text-center">
                                            <template x-if="!fileName">
                                                <p class="text-gray400">Click To Upload pdf</p>
                                            </template>
                                            <template x-if="fileName">
                                                <div class="">

                                                    <p class="text-gray-400" x-text="fileName"></p>
                                                    <p class="text-gray-400 text-sm mt-1 ">Click To Change Your Resume (Pdf only,Max 5MB)</p>
                                                </div>
                                                </template>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                          </div>
                    </div>

                    <button type="submit" name="apply" class="w-full mt-6 bg-indigo-500 text-white p-2 rounded-lg">
                        Apply Now
                    </button>
                </form>
            </div>



        </div>

</x-app-layout>  