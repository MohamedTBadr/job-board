<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-black shadow-lg rounded-lg p-6 max-w-7xl mx-auto">
            <h3 class="text-white text-2xl font-bold">
                    {{ __('welcome back,')}}{{ auth()->user()->name  }}
            </h3>

            <div class="flex items-center justify-between">
                <form action="{{route('dashboard')}}" method="get" class="flex felx-items justify-center w-1/4">
                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-800 text-white w-full p-2 rounded-l-lg" placeholder="Search For Job" id="">
                    <button type="submit"  class="bg-indigo-500 text-white p-2 rounded-r-lg border border-indigo-500"> Search</button>
                    @if(request('search'))
                            <a href="{{ route('dashboard',['filter'=>request('filter')]) }}" class="ml-4 bg-red-500 text-white p-2 rounded-lg">clear</a>
                    @endif
                    
                </form>
                    <!-- 'Full-Time','Contract','Remote','Hybrid' -->
                <div class="flex space-x-2">
                    <a class="bg-indigo-500 text-white p-2 rounded-lg" href="{{ route('dashboard',['filter'=>'Full-Time','search'=>request('search')]) }}">Full Time</a>
                    <a class="bg-indigo-500 text-white p-2 rounded-lg" href="{{ route('dashboard',['filter'=>'Remote','search'=>request('search')])  }}">remote </a>
                    <a class="bg-indigo-500 text-white p-2 rounded-lg" href="{{ route('dashboard',['filter'=>'Hybrid','search'=>request('search')]) }}">Hybrid</a>
                    <a class="bg-indigo-500 text-white p-2 rounded-lg" href="{{ route('dashboard',['filter'=>'Contract','search'=>request('search')]) }}">Contract</a>
                    @if(request('filter'))
                        <a href="{{ route('dashboard',['search'=>request('search')]) }}" class="bg-red-500 text-white p-2 rounded-lg">clear</a>
                @endif
                </div>
            </div>

            <div class="space-y-4 mt-6">
                @forelse($Jobs as $job)
                <div class="border-b border-white/10 pb-4 flex justify-between items-center">
                    <div>
                        <a href="{{ route('job-vacancies.show',$job->id) }}" class="text-lg font-semibold text-blue-400 hover:underline">{{ $job->title }}</a>
                        <p class="text-sm text-white">{{ $job->company->name }} - {{ $job->location }}</p>
                        <p class="text-sm text-white">${{number_format( $job->salary) }}</p>
                        
                    </div>
                    <span class="bg-blue-500 text-white p-2 rounded-lg">{{ $job->type }}</span>
                </div>
                @empty
                <p class="text-white">No JOBS FOUND</p>
                @endforelse
            </div>
        </div>
        <div class="mt-4">
        {{ $Jobs->Links() }}
        </div>
    </div>
</x-app-layout>
