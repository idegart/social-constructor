<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successResponse(array $data = [], int $status = Response::HTTP_OK)
    {
        $response = collect([
            'success' => true,
        ]);

        return response()->json($response->merge($data), $status);
    }

    public function errorResponse(array $data = [], int $status = Response::HTTP_BAD_REQUEST)
    {
        $response = collect([
            'success' => false,
        ]);

        return response()->json($response->merge($data), $status);
    }
}
