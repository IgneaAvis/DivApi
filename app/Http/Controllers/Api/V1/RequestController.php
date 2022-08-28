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
    public function index(AddingRequest $request)
    {
        $response = \App\Models\Request::create($request->validated());
        return response()->json($response, 201);
    }

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
        } else {
            return RequestResource::collection(\App\Models\Request::all());
        }
    }

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
