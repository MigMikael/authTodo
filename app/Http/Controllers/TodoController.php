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
        $this->middleware('jwt.auth'/*, ['except' => ['index']]*/);
    }

    public function index()
    {
        $payload = JWTAuth::getPayload();
        //$tokenExpireTime = $payload['exp'];
        $tokeniat = $payload['iat'];
        $currentTime = time();

        /*Log::info('#### Todo | index | Token Expire Time '.date("Y-m-d H:i:s", $tokenExpireTime));
        Log::info('#### Todo | index | The Current Time '.Carbon::now());*/

        //Log::info('#### Todo | index | Token Expire Time '.$tokenExpireTime);
        Log::info('#### Todo | index | Token iat '.$tokeniat);
        Log::info('#### Todo | index | The Current Time '.$currentTime);

        $time = ($currentTime - $tokeniat)/60;
        Log::info('#### Todo | index | Difference Time '.$time);
        if($time < 5){
            Log::info('#### Token is valid');
            $user = JWTAuth::parseToken()->authenticate();
            $todos = Todo::where('owner_id', $user->id)->get();
            return $todos;
        }else{
            Log::info('#### Token is expired');
            return response()->json(['token_expired']);
        }
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
