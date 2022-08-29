<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Авторизация (Получение токена)",
     *     tags={"Авторизация"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={
     *                      "email",
     *                      "password"
     *                  },
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      example="test@gmail.com"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      example="testpassword123"
     *                  )
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="successful operation"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $creds = $request->only(['email', 'password']);
        if(!$token = auth()->attempt($creds)){
            return response()->json(['error' => true, 'message' => 'Incorrect Login/Password'], 401);
        }
        return response()->json(['token' => $token]);
    }
}
