<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "API REST para la gestión de categorías y productos de la prueba técnica Overskull.",
    title: "Overskull API"
)]
#[OA\Server(
    url: "http://127.0.0.1:8000/api",
    description: "Servidor de Desarrollo Local"
)]

abstract class Controller
{
    use ApiResponse;

    //
}
