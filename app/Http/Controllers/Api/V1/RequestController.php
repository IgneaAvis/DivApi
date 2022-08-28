<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $response = \App\Models\Request::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'message' => $request->get('message')
        ]);
        if ($response) {
            return response()->json(['error' => false, 'message' => 'Application successfully created.'], 201);
        } else {
            return response()->json(['error' => true, 'message' => 'error'], 400);
        }
    }

    public function getAllRequests(Request $request)
    {
        if ($request->has('status') && $request->has('order')) {
            return response()->json(\App\Models\Request::select()->
            where('status', $request->get('status'))->
            orderBy('created_at', $request->get('order'))->
            get());
        } elseif ($request->has('status')) {
            return response()->json(\App\Models\Request::select()->
            where('status', $request->get('status'))->
            get());
        } else {
            return response()->json(\App\Models\Request::all());
        }
    }

    public function postAnswer($id, Request $request)
    {
        $item = \App\Models\Request::find($id);
        $putMessage = $item->update([
            'comment' => $request->get('comment'),
            'status' => 'Resolved',
            'updated_at' => date('Y-m-d h:i:s')
        ]);
        if ($putMessage) {
            return response()->json(['error' => false, 'message' => 'Data updated successfully'], 201);
        } else {
            return response()->json(['error' => true, 'message' => 'error'], 400);
        }
    }
}
