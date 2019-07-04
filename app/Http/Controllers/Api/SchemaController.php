<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BlockResource;
use App\Models\Schema;
use App\Http\Controllers\Controller;

class SchemaController extends Controller
{
    public function show()
    {

    }

    public function blocks(Schema $schema)
    {
        return $this->successResponse([
            'blocks' => BlockResource::collection($schema->blocks)
        ]);
    }
}
