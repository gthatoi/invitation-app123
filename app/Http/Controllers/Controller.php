<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function successResponse(JsonResource $data, bool $resourceCreated = false): JsonResponse
    {
        return response()->json(
            $data,
            $resourceCreated
                ? Response::HTTP_CREATED
                : Response::HTTP_OK
        );
    }

    protected function emptySuccessResponse(): JsonResponse
    {
        return response()->json(null, Response::HTTP_OK);
    }

    protected function errorResponse(string $errorMessage, int $responseCode): JsonResponse
    {
        return response()->json(
                ['error' => $errorMessage],
                $responseCode
            );
    }
}
