<?php

namespace App\Http\Controllers;

use App\Facades\QueryRunner;
use App\Http\Requests\QueryRequest;
use App\Http\Requests\RunQueryRequest;
use App\Http\Resources\CommonResource;
use App\Http\Resources\QueryResource;
use App\Models\Query;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    public function __construct(public $runnable_query)
    {
        $this->authorizeResource(Query::class, options: [
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
            'queries' => function ($query) use ($request) {
                return $query->when($request->has('form_id') && $request->query('form_id') === null, fn ($query) => $query->whereNull('form_id'));
            }, 'queries.connection'
        ]);

        return QueryResource::collection($user->queries);
    }

    public function store(QueryRequest $request)
    {
        $dataset = $request->safe()->all();

        $query = new Query($dataset);

        $query->connection()->associate($request->input('connection_id'));

        $query->save();

        $query->refresh();

        return new QueryResource($query);
    }

    public function show(Query $query)
    {
        return new QueryResource($query);
    }

    public function update(QueryRequest $request, Query $query)
    {
        $query->connection()->associate($request->input('connection_id'));

        /*
         * Updating query means user solved query problems.
         */
        $query->status = true;
        $query->sql = $request->input('sql');
        $query->type = $request->input('type');
        $query->parameters = $request->input('parameters');
        $query->button_title = $request->input('button_title');

        $query->save();
    }

    public function destroy(Query $query)
    {
        $query->delete();
    }

    public function run(RunQueryRequest $request): CommonResource
    {
        $values = $request->input('values');

        $result = QueryRunner::setValues($values)->run($this->runnable_query);

        return new CommonResource($result);
    }
}
