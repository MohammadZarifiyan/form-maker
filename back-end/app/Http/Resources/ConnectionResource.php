<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConnectionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->database,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
