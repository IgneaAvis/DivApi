<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="DivApi Docs",
     *      description="Документация для api",
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="API/V1"
     * )
     *
     * @OA\Tag(
     *     name="Requests",
     *     description="Работа с заявками"
     * )
     */

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        auth()->setDefaultDriver('api');
    }
}
