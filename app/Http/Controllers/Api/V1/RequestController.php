<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddingRequest;
use App\Http\Requests\PostAnswerRequest;
use App\Http\Resources\RequestResource;
use App\Mail\UpdateMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RequestController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/requests",
     *     summary="Создание заявки",
     *     tags={"Заявки"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={
     *                      "name",
     *                      "email",
     *                      "message"
     *                  },
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      example="Константин"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      example="kogrebenkin@gmail.com"
     *                  ),
     *                  @OA\Property(
     *                      property="message",
     *                      type="string",
     *                      example="Test message"
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

    public function index(AddingRequest $request)
    {
        $response = \App\Models\Request::create($request->validated());
        return response()->json($response, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/requests",
     *     summary="Получение заявок (Нужен токен)",
     *     tags={"Заявки"},
     *     @OA\Parameter(
     *          in="header",
     *          name="api_key",
     *          description="Ваш api token",
     *          required=true
     *     ),
     *     @OA\Parameter(
     *          in="path",
     *          name="status",
     *          description="Статус заявки Resolved|Active",
     *     ),
     *     @OA\Parameter(
     *          in="path",
     *          name="order",
     *          description="Сортировка заявок",
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation"
     *     )
     * )
     */

    public function getAllRequests(Request $request)
    {
        if ($request->has('status') && $request->has('order')) {
            return response()->json(RequestResource::collection(\App\Models\Request::select()->
            where('status', $request->get('status'))->
            orderBy('created_at', $request->get('order'))->
            get()));
        } elseif ($request->has('status')) {
            return response()->json(RequestResource::collection(\App\Models\Request::select()->
            where('status', $request->get('status'))->
            get()));
        } elseif ($request->has('order')) {
            return response()->json(RequestResource::collection(\App\Models\Request::select()->
            orderBy('created_at', $request->get('order'))->
            get()));
        } else {
            return RequestResource::collection(\App\Models\Request::all());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/requests/{id}",
     *     summary="Добавление ответа к заявке (Нужен токен)",
     *     tags={"Заявки"},
     *     @OA\Parameter(
     *          in="path",
     *          name="id",
     *          description="Id заявки",
     *          example=1
     *     ),
     *     @OA\Parameter(
     *          in="header",
     *          name="api_key",
     *          description="Ваш api token",
     *          required=true
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={
     *                      "comment"
     *                  },
     *                  @OA\Property(
     *                      property="comment",
     *                      type="string",
     *                      example="Test comment"
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

    public function postAnswer($id, PostAnswerRequest $request)
    {
        $item = \App\Models\Request::find($id);
        if ($request->validated()) {
            $putMessage = $item->update([
                'comment' => $request->get('comment'),
                'status' => 'Resolved',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
//      Кривая, но рабочая реализация имитации отправки письма
        $name = $item->getAttributeValue('name');
        $msg = $item->getAttributeValue('message');
        $comment = $request->get('comment');
        $items = [$name, $msg, $comment];
        Mail::to($item->getAttributeValue('email'))->send(new UpdateMessage($items));

        return response()->json($item, 201);
    }
}
