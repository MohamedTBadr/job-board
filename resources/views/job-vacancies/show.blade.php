<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{$jobVacancy->title}} - Details
        </h2>
    </x-slot>

        <div class="py-12">
            <div class="bg-black shadow-lg rounded-lg p-6 max-w-7xl mx-auto">
            <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline mb-6 inline-block"> ← Back To Jobs</a>

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

                    <div>
                        <a href="
                        {{ route('job-vacancies.apply',$jobVacancy->id) }}
                        " class="bg-indigo-500 text-white p-2 rounded-lg">Apply Now</a>
                    </div>
                </div>
            </div>


            <div class="grid grid-flow-col grid-cols-3 gap-4 mt-6">
                <div class="row-span-3 col-span-2">
                    <h2 class="text-lg fond-bold text-white">Job Description</h2>
                    <p class="text-gray-400">{{ $jobVacancy->description }}</p>
                </div>
                <div class="col-span-1">
                    
                    <h2 class="text-lg fond-bold text-white">Job Overview</h2>
                    <div class="text-gray-400 rounded-lg p-6 space-y-4">
                        <div>
                            
                            <p>Created at</p>
                            <p>{{ $jobVacancy->created_at->format('M,d,Y')}}</p>
                        </div>
                        <div>
                            
                            <p>   Company Name</p>
                            <p>{{ $jobVacancy->company->name }}</p>
                        </div>
                        <div>
                            
                            <p>  Location</p>
                            <p>{{ $jobVacancy->location }}</p>
                        </div>
                        <div>
                            
                            <p>  salary</p>
                            <p>${{ number_format($jobVacancy->salary) }}</p>
                        </div>
                        <div>
                            
                            <p> Type</p>
                            <p>{{ $jobVacancy->type }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            </div>
        </div>
        
    </x-app-layout>
