<?php

namespace App\Exceptions;

use App\Models\Query;
use Exception;
use Illuminate\Support\Facades\Log;

class QueryException extends Exception
{
    public function report()
    {
        $query_id = $this->getMessage();

        Query::where('id', '=', $query_id)->update([
            'status' => false,
        ]);

        Log::alert('Used query has some problem.', [
            'id' => $query_id,
        ]);
    }
}
