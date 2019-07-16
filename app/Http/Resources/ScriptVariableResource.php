<?php

namespace App\Http\Resources;

use App\Models\Script\ScriptVariable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScriptVariableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'variable' => $this->resource->variable,
            'type' => $this->resource->type,
            'default' => $this->resource->default,
        ];
    }
}
