<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Block\StoreRequest;
use App\Http\Requests\Api\Block\UpdateRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlockResource;
use App\Models\Block;
use App\Models\Schema;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BlockController extends Controller
{
    public function show(Block $block)
    {
        return $this->successResponse([
            'block' => new BlockResource($block)
        ]);
    }

    public function store(StoreRequest $request)
    {
        $schema = Schema::findOrFail($request->get('schema_id'));

        $block = $schema->blocks()->create($request->only(['top', 'left', 'data_type']));

        return $this->successResponse([
            'block' => new BlockResource($block)
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateRequest $request, Block $block)
    {
        $block->update($request->validated());

        return $this->successResponse([
            'block' => new BlockResource($block->fresh())
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * @param Block $block
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Block $block)
    {
        if ($block->delete()) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }
}
