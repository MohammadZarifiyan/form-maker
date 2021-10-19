<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QueryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'connection' => new ConnectionResource($this->connection),
            'form_id' => $this->form_id,
            'id' => $this->id,
            'sql' => $this->sql,
            'type' => $this->type,
            'parameters' => $this->parameters,
            'button_title' => $this->button_title,
            'status' => $this->status,
        ];
    }
}
