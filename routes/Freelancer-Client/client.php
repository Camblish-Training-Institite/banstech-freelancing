 <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Jobs\ContractController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Client\ContestController;
use App\Http\Controllers\Freelancer\MilestoneController;
use App\Http\Controllers\Jobs\JobsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Jobs\ProposalController; 
use App\Http\Controllers\Payments\ProjectFundingController;
use App\Models\Contract;
use App\Models\ProjectFunding;

Route::get('/client/job/subCategories', [CategoryController::class, 'getSubcategories'])->name('client.jobs.subcategories');

Route::prefix('client')->name('client.')->group(function () {
    //client main nav links

    //cancel and complete buttons
    Route::post('/project/{project}/completed', [ContractController::class, 'completeContract'])->name('projects.completed');
    Route::post('/project/{project}/cancel', [ContractController::class,'cancelContract'])->name('projects.cancel');

    //client job routes
    Route::get('/my-jobs', [JobsController::class, 'index_client'])->name('jobs.list');
    Route::resource('jobs', JobsController::class);
    

    //client project routes
    Route::get('/my-projects', [ContractController::class, 'index_client'])->name('projects.list');
    Route::get('/project/{id}/show', [ContractController::class, 'show_client'])->name('project.show');
    Route::resource('projects', ContractController::class)->except(['index', 'show']);

    //client milestone routes
    Route::get('/project/{project}/milestones', [ContractController::class, 'milestones'])->name('project.milestones');
    Route::get('/project/{project}/milestones/create', [MilestoneController::class, 'create'])->name('project.milestones.create');
    Route::post('/project/{project}/milestones/store', [MilestoneController::class, 'store'])->name('project.milestone.store');
    Route::get('/project/{project}/milestones/edit/{milestone}', [MilestoneController::class, 'edit'])->name('project.milestone.edit');
    Route::post('/project/{project}/milestones/store/{milestone}', [MilestoneController::class, 'update'])->name('project.milestone.update');
    Route::get('/project/{project}/milestones/destroy/{milestone}', [MilestoneController::class, 'destroy'])->name('project.milestone.destroy');
    Route::get('/project/{project}/milestones/destroy/{milestone}', [MilestoneController::class, 'releasePayment'])->name('project.milestone.release');
  
    //Proposal Routes
    Route::get('/proposals-list', [ProposalController::class, 'index_client'])->name('proposals.list');
    Route::resource('/proposals', ProposalController::class);
    Route::get('/proposal/{job}/show', [ProposalController::class, 'job_show'])->name('proposals.job.show');
    Route::get('/proposal/{proposal}/accept', [ProposalController::class, 'acceptProposal'])->name('proposals.accept');
    Route::get('/proposal/{proposal}/reject', [ProposalController::class, 'rejectProposal'])->name('proposals.reject');

    //Freelacner Profile Routes
    Route::get('/freelancer/{freelancerId}/profile', [ProfileController::class, 'viewFreelancerProfile'])->name('freelancer.profile');

    //client profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //client services routes
    Route::get('/services',function(){
    return view('Users.Clients.pages.services');
    })->name('services');

    //Message or Inbox routes
    Route::get('/inbox', function(){
        return view('Users.Clients.pages.inbox');
    })->name('inbox');

    //Earnings routes
    Route::get('/earnings',function(){
        
        return view('Users.Clients.pages.earnings');
    })->name('earnings');

})->middleware('auth');

//client contest routes
Route::prefix('client/contests')->name('client.contests.')->group(function () {
    Route::get('/', [ContestController::class, 'index'])->name('index');
    Route::get('/create', [ContestController::class, 'create'])->name('create');
    Route::post('/store', [ContestController::class, 'store'])->name('store');
    Route::get('/{contest}/edit', [ContestController::class, 'edit'])->name('edit');
    Route::patch('/{contest}', [ContestController::class, 'update'])->name('update');
    Route::delete('/{contest}', [ContestController::class, 'destroy'])->name('destroy');
    Route::get('/{contest}/show', [ContestController::class, 'show'])->name('show');
})->middleware('auth');

//This is for billing page situated under dashboards/clients...
// Route::get('/client/billing',function(){
//     return view('dashboards.client.billing');
// })->name('billing');

//testing route for billing page
Route::get('client/billing', [ProjectFundingController::class, 'index'])->name('billing')->middleware('auth');

// Show project page (with file upload form)
// Route::get('/Users/Clients/project/{project}/show', [ContractController::class, 'show'])->name('client.project.show')->middleware('auth');

//Handle file uploads
Route::post('Clients/project/{project}/upload-file', [ContractController::class, 'uploadFile'])->name('client.project.tabs.file')->middleware('auth');

//Handle task creation
Route::post('Clients/project/{project}/task', [ContractController::class, 'createTask'])
->name('client.project.tabs.task')->middleware('auth');

