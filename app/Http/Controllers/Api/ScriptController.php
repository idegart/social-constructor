<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Script\StoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Script\StoreVariableRequest;
use App\Http\Resources\SchemaResource;
use App\Http\Resources\ScriptResource;
use App\Http\Resources\ScriptVariableResource;
use App\Models\Script;
use Exception;
use Illuminate\Http\JsonResponse;
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

    public function variables(Script $script)
    {
        return $this->successResponse([
            'variables' => ScriptVariableResource::collection($script->variables()->get())
        ]);
    }

    public function storeVariable(StoreVariableRequest $request, Script $script)
    {
        $variable = $script->variables()->create($request->validated());

        return $this->successResponse([
            'variable' => new ScriptVariableResource($variable)
        ]);
    }

    /**
     * @param Script $script
     * @param $scriptVariableId
     * @return JsonResponse
     * @throws Exception
     */
    public function removeVariable(Script $script, $scriptVariableId)
    {
        $variable = $script->variables()->findOrFail($scriptVariableId);

        $variable->delete();

        return $this->successResponse();
    }
}
