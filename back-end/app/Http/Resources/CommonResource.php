<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommonResource extends JsonResource
{
    public function toArray($request)
    {
        /*
         * Don't change this method.
         * Use this resource to format JSON data which are not used more than one time.
         */
        return parent::toArray($request);
    }
}
