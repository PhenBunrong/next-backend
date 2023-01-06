<?php

namespace App\Interfaces\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST,
 *         description="API Development Server"
 *     ),
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST_STAGING,
 *         description="API Staging Server"
 *     ),
 *     @OA\Info(
 *         version="0.0.1",
 *         title="Bus ticket booking API Documentation",
 *         description="API List for Bus ticket booking system",
 *         termsOfService="https://swagger.io/terms/",
 *         @OA\Contact(name="Chantouch Sek", email="chantouchsek.cs83@gmail.com", url="https://chantouch.me"),
 *         @OA\License(
 *              name="Apache 2.0",
 *              url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     security={
 *         {"bearer": {}},
 *     },
 * )
 */
interface AbstractControllerInterface
{
    public function index(Request $request): JsonResponse;

    public function store(Request $request): JsonResponse;

    public function show(Request $request, string $id): JsonResponse;

    public function update(Request $request, string $id): JsonResponse;

    public function destroy(string $id): JsonResponse;
}
