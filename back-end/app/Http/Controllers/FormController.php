<?php

namespace App\Http\Controllers;

use App\Facades\QueryRunner;
use App\Http\Requests\FormRequest;
use App\Http\Resources\CommonResource;
use App\Http\Resources\FormResource;
use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Form::class, options: [
            'except' => [
                'index',
                'store',
            ],
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $user->load([
            'forms' => fn ($query) => $query->when(
                $request->exists('autoload'),
                fn ($query) => $query->where('autoload', '=', $request->boolean('autoload'))
            ),
            'forms.queries' => fn ($query) => $query->when(
                $request->query('project_id'),
                fn ($query, $project_id) => $query->where('project_id', '=', $project_id)
            ),
        ]);

        return FormResource::collection($user->forms);
    }

    public function store(FormRequest $request)
    {
        $dataset = $request->safe()->all();

        $form = new Form($dataset);

        $form->project()->associate($request->input('project_id'));

        $form->save();

        $form->queries()->attach($request->input('queries'));

        $form->load('queries');

        return new FormResource($form);
    }

    public function show(Form $form)
    {
        /*
         * Autoload form means we need result of ran queries,
         * Non-Autoload form means we need queries to pass custom values to them and run.
         */
        if (!$form->autoload) {
            return new FormResource($form);
        }

        $queries_result = [];

        foreach ($form->queries as $query) {
            $run_result = QueryRunner::run($query);

            $queries_result[$query->id] = [
                'data' => $run_result,
            ];
        }

        $form_resource = new FormResource($form);

        return $form_resource->additional(compact('queries_result'));
    }

    public function update(FormRequest $request, Form $form)
    {
        $form->project()->associate($request->input('project_id'));

        $form->queries()->sync($request->input('queries'));

        $form->title = $request->input('title');

        $form->save();
    }

    public function destroy(Form $form)
    {
        $form->delete();
    }

    public function check(int $id, Request $request): CommonResource
    {
        $exists = Form::where('id', '=', $id)->where(function ($query) use ($request) {
            return $query->when(
                $request->exists('autoload'),
                fn ($query) => $query->where('autoload', '=', $request->boolean('autoload'))
            );
        })->exists();

        return new CommonResource([
            'exists' => $exists,
        ]);
    }
}
