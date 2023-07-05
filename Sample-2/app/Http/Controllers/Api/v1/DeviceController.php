<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceInfoRequest;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class DeviceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/devices",
     *     operationId="getDevicesList",
     *     tags={"Devices"},
     *     summary="Get list of devices",
     *     description="Returns list of devices",
     *     security={{"bearer_token":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 example="200",
     *                 type="integer"
     *             ),
     *             @OA\Property(
     *                 property="string",
     *                 example="2022-06-13 9:16",
     *                 type="string"
     *             )
     *         ),
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'date' => CarbonImmutable::now()->toDateTimeString()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/devices/info",
     *     operationId="getDeviceInfo",
     *     tags={"Devices"},
     *     summary="Get device info",
     *     description="Returns device info",
     *     security={{"bearer_token":{}}},
     *
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 example={"id": "1", "phone": 12345678}
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 example="200",
     *                 type="integer"
     *             ),
     *             @OA\Property(
     *                 property="device",
     *                 example="Xiaomi MI9",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 example="Apg",
     *                 type="string"
     *             )
     *         ),
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */
    public function info(DeviceInfoRequest $request)
    {
        $validated = $request->validated();

        $data = [
            'device' => 'Xioami MI9',
            'user' => 'Andrey APG'
        ];

        return response()->json($data, Response::HTTP_OK);
    }
}
