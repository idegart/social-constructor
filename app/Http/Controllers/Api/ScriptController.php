<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Script\StoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\SchemaResource;
use App\Http\Resources\ScriptResource;
use App\Models\Script;
use Illuminate\Http\Response;

class ScriptController extends Controller
{
    public function index()
    {
        $scripts = Script::all();

        return $this->successResponse([
            'scripts' => ScriptResource::collection($scripts)
        ]);
    }

    public function store(StoreRequest $request)
    {
        $script = Script::create(collect($request->validated())->except('accept')->toArray());

        return $this->successResponse([
            'script' => new ScriptResource($script)
        ], Response::HTTP_CREATED);
    }

    public function show(Script $script)
    {
        return $this->successResponse([
            'script' => new ScriptResource($script)
        ]);
    }

    public function schemas(Script $script)
    {
        return $this->successResponse([
            'schemas' => SchemaResource::collection($script->schemas)
        ]);
    }
}
