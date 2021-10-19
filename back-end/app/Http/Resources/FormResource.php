<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'queries' => QueryResource::collection($this->queries),
            'project_id' => $this->project_id,
            'id' => $this->id,
            'title' => $this->title,
            'autoload' => $this->autoload,
        ];
    }
}
