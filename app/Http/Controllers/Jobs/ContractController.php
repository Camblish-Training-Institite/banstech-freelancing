<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\Task;

class ContractController extends Controller
{
    public function index_client(){
        if(!Auth::check()){

        }

        $user = Auth::user();
        $clientId = Auth::user()->id;
        $projects = $user->contracts()->paginate(10);

        // dd($projects);

        return view('Users.Clients.layouts.project-section', ['projects' => $projects]);
    }

    public function index(){
        if(!Auth::check()){

        }

        $freelancerId = Auth::user()->id;
        $projects = Contract::where('user_id', $freelancerId)->get();
        return view('Users.Freelancers.layouts.job-section', ['projects' => $projects]);
    }

    public function show_client(int $id){
        $contract = Contract::find($id);
        if ($contract) {
            return view('Users.Clients.projects.view', ['project' => $contract, 'tab' => 'details']);
        }
        return redirect()->back()->with('error', 'Contract not found.');
    }

    public function show(int $id){
        $contract = Contract::find($id);
        if ($contract) {
            return view('Users.Freelancers.projects.view', ['project' => $contract, 'tab' => 'details']);
        }
        return redirect()->back()->with('error', 'Contract not found.');
    }

    public function requestMilestone(Contract $project)
    {
        // Return the view for requesting a milestone
        return view('Users.Freelancers.projects.request-milestone', ['project' => $project]);

    }

    public function create() {

    }

    public function store(Request $request){

    }

    public function edit(int $id){

    }

    public function update(Request $request, int $id){

    }

    public function cancelContract(Request $request, int $id){
        $contract = Contract::find($id);
        if ($contract && $contract->job->user->user_id == Auth::id()) {
            $contract->status = 'cancelled';
            $contract->save();
            return redirect()->back()->with('success', 'Contract cancelled successfully.');
        }
        return redirect()->back()->with('error', 'Contract not found or you do not have permission to cancel it.');
    }

    public function completeContract(Request $request, int $id){
        $contract = Contract::find($id);
        if ($contract && $contract->job->user->id == Auth::id()) {
            $contract->status = 'completed';
            $contract->save();
            return redirect()->back()->with('success', 'Contract completed successfully.');
        }
        return redirect()->back()->with('error', 'Contract not found or you do not have permission to complete it.');
    }

    public function uploadFile(Request $request, Contract $project)
    {
        // dd($request->all());
        // Validate the request
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx,jpg,jpeg,png|max:10240' // 10MB max
        ]);

        $file = $request->file('file');

        // Store file in disk
        $path = $file->store('projects/' . $project->id, 'public'); // e.g., projects/123/file.pdf

        // Get file info
        $fileName = $file->getClientOriginalName();
        //$fileSize = formatBytes($file->getSize()); // Helper function below

        // Save to database
        $uploadedFile = new File([
            'file_name' => $fileName,
            'file_path' => $path,
            // 'file_size' => $fileSize,
            'project_id' => $project->id,
            'user_id' => auth()->id(),
        ]);
        $uploadedFile->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'File uploaded successfully!');


    }

    public function createTask(Request $request, Contract $project)
    {
        //dd($request->all());
        $request->validate([
            'title' => '|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => 'pending',
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'due_date' => $request->input('due_date'),
        ]);
        $task->save();

        return redirect()->route('client.project.show',$project->id)->with('success', 'Task created successfully!');
    }

    public function clientProjectTasks(Request $request, Contract $project)
    {
        $tasks = $project->tasks()->with('user')->get();
        return view('Users.Clients.projects.tabs.tasks', ['project' => $project, 'tasks' => $tasks]);
    }
}
