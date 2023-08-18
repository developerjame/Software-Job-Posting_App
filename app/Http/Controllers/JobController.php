<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class JobController extends Controller
{
    //Home page
    public function index(){
        return view('jobs.index', ['jobs'=>Job::latest()->filter(request(['search', 'tag']))->paginate(2)]);
    }

    //Show create job form
    public function create(){
        return view('jobs.create');
    }

    //Create and store job
    public function store(Request $request){
        $formFields = $request->validate([
            'company' => ['required', Rule::unique('jobs', 'company')],
            'title' => 'required',
            'tags' => 'required',
            'email' => ['required', 'email'],
            'website' => 'required',
            'location' => 'required',
            'description' => 'required'
        ]);
        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Job::create($formFields);

        return redirect('/')->with('message', 'Job created successfully!');
    }

    //Edit job
    public function edit(Job $job){
        return view('jobs.edit', ['job'=>$job]);
    }

    //Submit to update job
    public function update(Request $request, Job $job){
        $formFields = $request->validate([
            'company' => 'required',
            'title' => 'required',
            'tags' => 'required',
            'email' => ['required', 'email'],
            'website' => 'required',
            'location' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')){
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $job->update($formFields);

        return redirect('/')->with('message', 'Job updated successfully!');
    }

    //Delete job
    public function delete(Job $job){
        $job->delete();
        if($job->user_id != auth()->id()){
            abort('403', 'Unauthorised action!');
        }

        return back()->with('message', 'Job deleted successfully!');
    }

    //Manage jobs
    public function manage(){
        return view('jobs.manage', ['jobs'=>auth()->user()->jobs()->get()]);
    }

    //Show single job
    public function show(Job $job){
        return view('jobs.show', ['job'=>$job]);
    }
}
