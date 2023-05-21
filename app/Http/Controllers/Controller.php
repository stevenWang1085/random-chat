<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseMaker($code, $message, $data): JsonResponse
    {
        $result = ResponseHelper::responseMaker($code, $message, $data);

        return response()->json($result, $result['http_status_code'])
            ->header('Content-Type', 'application/json');
    }
}
