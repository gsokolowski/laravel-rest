<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Todo;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;



class TodoController extends Controller
{

    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['index']]);

        // create a log channel
        $log = new Logger('name');
        $log->pushHandler(new StreamHandler('storage/logs/app.log', Logger::WARNING));

        // add records to the log
        $log->addWarning('todo rest called');
    }

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $todos = Todo::where('owner_id', $user->id)->get();

        return response()->json(compact('todos'));
        //return $todos;
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $newTodo = $request->all();
        $newTodo['owner_id']=$user->id;
        return Todo::create($newTodo);
    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $todo = Todo::where('owner_id', $user->id)->where('id',$id)->first();

        if($todo){
            $todo->is_done=$request->input('is_done');
            $todo->save();
            return $todo;
        }else{
            return response('Unauthoraized',403);
        }
    }

    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $todo = Todo::where('owner_id', $user->id)->where('id',$id)->first();

        if($todo){
            Todo::destroy($todo->id);
            return  response('Success',200);;
        }else{
            return response('Unauthoraized',403);
        }
    }
}