<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelancer\JobController;
use App\Http\Controllers\Freelancer\ProposalController;
use App\Http\Controllers\Freelancer\ContestController;
use App\Http\Controllers\Freelancer\CompletedProjectsController;
use App\Http\Controllers\Freelancer\NextOpportunitiesController;
use App\Http\Controllers\Freelancer\NewOffersController;
use App\Http\Controllers\Freelancer\ViewOffersController;
use App\Http\Controllers\Freelancer\EarningsController;

if (env('SESSION_DIAGNOSTICS', false)) {
    Route::get('/__session-debug', function (Request $request) {
        $response = response()->json([
            'app_env' => config('app.env'),
            'app_url' => config('app.url'),
            'app_key_present' => filled(config('app.key')),
            'session_driver' => config('session.driver'),
            'session_domain' => config('session.domain'),
            'session_secure' => config('session.secure'),
            'session_same_site' => config('session.same_site'),
            'request_host' => $request->getHost(),
            'request_scheme' => $request->getScheme(),
            'request_is_secure' => $request->isSecure(),
            'has_session_cookie_in_request' => $request->hasCookie(config('session.cookie')),
            'has_xsrf_cookie_in_request' => $request->hasCookie('XSRF-TOKEN'),
            'session_cookie_name' => config('session.cookie'),
            'session_id' => $request->session()->getId(),
            'csrf_token_prefix' => substr(csrf_token(), 0, 12),
            'cache_control' => $request->headers->get('cache-control'),
        ]);

        return $response->cookie(
            'banstech_diag',
            'ok',
            10,
            config('session.path'),
            config('session.domain'),
            (bool) config('session.secure'),
            false,
            false,
            config('session.same_site')
        );
    })->name('session.debug');
}

Route::get('/', function () {
    return view('landing');
})->name('welcome');

Route::get('/freelancer-hub', function () {
    return view('welcome');
})->name('freelance.home');

Route::get('/freelancer/dashboard', function () {
    return redirect()->route('freelancer.projects.list');
})->middleware(['auth'])->name('freelancer.dashboard');

Route::get('/client/dashboard', function () {
    return redirect()->route('client.jobs.list');
})->middleware(['auth'])->name('client.dashboard');

Route::middleware('auth')->group(function () {
    

Route::get('/freelancer/active-jobs', [JobController::class, 'activeJobs'])
        ->name('freelancer.active-jobs');

        Route::get('freelancer/proposals', [ProposalController::class, 'proposals'])
        ->name('freelancer.proposals');

        Route::get('freelancer/contests', [ContestController::class, 'contests'])
        ->name('freelancer.contests');

        Route::get('freelancer/completed-projects', [CompletedProjectsController::class, 'completedProjects'])
        ->name('freelancer.completed-projects');

        Route::get('Components/find-next-opportunity', [NextOpportunitiesController::class, 'nextOpportunities'])
        ->name('Components.find-next-opportunity');

        Route::get('Components/new-offer-card', [NewOffersController::class, 'newOffers'])
        ->name('Components.new-offer-card');

        Route::get('Components/new-offer-card', [NewOffersController::class, 'newOffers'])
        ->name('Components.new-offer-card');

        Route::get('Components/view-offer', [ViewOffersController::class, 'viewOffers'])
        ->name('Components.view-offer');

        Route::get('freelancer/earnings', [EarningsController::class, 'myEarnings'])
        ->name('freelancer.earnings');
});

Route::get('project-manager/dashboard', function() {
    return view('dashboards.manager-dashboard');
})->name('manager.dashboard');

// Route::get('/category', [CategoryController::class, 'index'])
//     ->name('Components.category');

require __DIR__.'/auth.php';
require __DIR__.'/jobs/jobRoutes.php';
require __DIR__.'/jobs/jobMapRoutes.php';
require __DIR__.'/jobs/notifications.php';
require __DIR__.'/admin/project-manager.php';
require __DIR__.'/admin/admin-routes.php';
require __DIR__.'/Freelancer-Client/freelancer.php';
require __DIR__.'/Freelancer-Client/client.php';
require __DIR__.'/api.php';
