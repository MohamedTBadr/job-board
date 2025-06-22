<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My-Applications') }}
        </h2>
    </x-slot>

    @if(session('success'))
    <div class="w-full bg-green-800 text-white p-4 rounded mdmb-2">
        {{ session('success') }}
    </div>
    @endif




    <div class="py-12">
        <div class="bg-black shadow-lg rounded-lg p-6 max-w-7xl mx-auto space-y-4">
            @forelse($jobApplications as $app)
            <div class="bg-gray-800 p-4 rounded-lg">
                <h3 class="text-white text-lg font-bold">
                    {{ $app->jobVacancy->title }}
                    
                </h3>
                <p class="text-sm"> {{ $app->jobVacancy->company->name }}</p>
                <p class="text-xs">{{ $app->location }}</p>
                <div class="flex items-center justify-between">
                    <p class="text-sm">{{ $app->created_at->format('D M Y') }}</p>
                    <p class="px-3 py">{{ $app->type }}</p>
                </div>

                <div class="flext items-center gap-2">
                    <span>
                        Applied With: {{$app->resume->file_name  }}
                        <a href="{{ Storage::disk('cloud')->url($app->resume->fileUrl) }}" target="_blank"> View Resume</a>
                    </span>
                </div>

                <div class="flex flex-start flex-col gap-2">
                    <div class="flex items-center gap-4">
                        @php
                            $status=$app->status;
                            $statusClass=match($status){
                                'Pending'=>'bg-yellow-500',
                                'Rejected'=>'bg-red-500',
                                'Accepted'=>'bg-green-500'
                            }

                        @endphp
                        <p class="text-sm {{ $statusClass }} w-fit rounded-md ">Status:{{ $app->status }}</p>
                        <p class="text-sm bg-indigo-600 text-white p-2 rounded-md w-fit">Score: {{   $app->AI_Generated_score }}</p>
                    </div>
                    <h4 class="text-md">AI Feedback:</h4>
                    <p class="text-sm"> {{   $app->AI_Generated_feedback}}</p>
                    
                </div>

            </div>
            @empty
            <div class="bg-gray-800 p-4 rounded-lg">

            <p class="text-white">No Job Applications found</p>
            </div>
            @endforelse
        </div>

{{ $jobApplications->links() }}
    </div>
</x-app-layout>