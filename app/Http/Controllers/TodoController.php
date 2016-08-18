<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth;
use Log;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index']]);
    }

    public function index()
    {
        Log::info('#### Todo | index');
        $user = JWTAuth::parseToken()->authenticate();
        $todos = Todo::where('owner_id', $user->id)->get();
        return $todos;
    }

    public function store(Request $request)
    {
        Log::info('#### Todo | store');
        $user = JWTAuth::parseToken()->authenticate();
        $newTodo = $request->all();
        $newTodo['owner_id'] = $user->id;
        return Todo::create($newTodo);
    }

    public function update(Request $request, $id)
    {
        Log::info('#### Todo | update');
        $user = JWTAuth::parseToken()->authenticate();
        $todo = Todo::where('owner_id', $user->id)->where('id', $id)->first();

        if ($todo) {
            $todo->is_done = $request->input('is_done');
            $todo->save();
            return $todo;
        } else {
            return response('Unauthorized', 403);
        }
    }

    public function destroy($id)
    {
        Log::info('#### Todo | destroy');
        $user = JWTAuth::parseToken()->authenticate();
        $todo = Todo::where('owner_id', $user->id)->where('id', $id)->first();

        if($todo) {
            Todo::destroy($todo->id);
            return response('Success', 200);
        } else {
            return response('Unauthorized', 403);
        }
    }
}
