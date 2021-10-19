<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, options: [
            'except' => [
                'index',
                'store',
            ],
        ]);
    }

    public function index(Request $request)
    {
        return ProjectResource::collection($request->user()->projects);
    }

    public function store(ProjectRequest $request)
    {
        $project = new Project($request->safe()->all());

        $project->user()->associate($request->user());

        $project->save();

        return new ProjectResource($project);
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->safe()->all());
    }

    public function destroy(Project $project)
    {
        $project->delete();
    }
}
