<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationsController;
use App\Http\Controllers\JobVacanciesController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth','role:job_seeker'])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/job-applications' ,[JobApplicationsController::class,'index'])->name('job-applications.index');
    Route::get('/job-vacancies/{id}',[JobVacanciesController::class , 'show'])->name('job-vacancies.show');

    Route::get('/job-vacancies/apply/{id}',[JobVacanciesController::class,'apply'])->name('job-vacancies.apply');
    Route::post('/job-vacancies/apply/{id}',[JobVacanciesController::class,'processApplication'])
    ->name('job-vacancies.process-application');


    //Testing OpenAi
    // Route::get('/test-OpenAI',[JobVacanciesController::class,'testOpenAI'])->name('test-OpenAI');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
