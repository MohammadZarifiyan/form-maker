<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConnectionRequest;
use App\Http\Resources\ConnectionResource;
use App\Models\Connection;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Connection::class, options: [
            'except' => [
                'index',
                'store',
            ],
        ]);
    }

    public function index(Request $request)
    {
        return ConnectionResource::collection($request->user()->connections);
    }

    public function store(ConnectionRequest $request)
    {
        $connection = new Connection($request->safe()->all());

        $connection->user()->associate($request->user());

        $connection->save();

        return new ConnectionResource($connection);
    }

    public function update(ConnectionRequest $request, Connection $connection)
    {
        $connection->update($request->safe()->all());
    }

    public function destroy(Connection $connection)
    {
        $connection->delete();
    }
}
