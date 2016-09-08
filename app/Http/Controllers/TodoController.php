<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Todo;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

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

    // works fine -- tested
    public function index()
    {

//        try {
//
//            if (! $user = JWTAuth::parseToken()->authenticate()) {
//                return response()->json(['user_not_found'], 404);
//            } else {
//                $todos = Todo::where('owner_id', $user->id)->get();
//                return response()->json(compact('todos'));
//            }
//        } catch (TokenExpiredException $e) {
//
//            return response()->json(['token_expired'], $e->getStatusCode());
//
//        } catch (TokenInvalidException $e) {
//
//            return response()->json(['token_invalid'], $e->getStatusCode());
//
//        } catch (JWTException $e) {
//
//            return response()->json(['token_absent'], $e->getStatusCode());
//
//        }


        // Done as PDO mode --------------------------------


        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            } else {
                // PDO mode --------------------------------
                // $todos is an array of objects (each row is separate object)
                $todos = DB::select('select * from todos where id < ?', [37]);
                return response()->json(compact('todos'));
            }
        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }


    }

    // works fine -- tested
    public function show(Request $request, $id) {
        $user = JWTAuth::parseToken()->authenticate();
        $todo = Todo::where('owner_id', $user->id)->where('id',$id)->first();

        return response()->json(compact('todo'));
    }

    // works
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $newTodo = $request->all();
        $newTodo['owner_id']=$user->id;
        return Todo::create($newTodo);
    }

    // works
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $todo = Todo::where('owner_id', $user->id)->where('id',$id)->first();

        if($todo){
            $todo->description=$request->input('description');
            $todo->is_done=$request->input('is_done');
            $todo->created_at=$request->input('created_at');
            $todo->updated_at=$request->input('updated_at');
            $todo->save();
            return $todo;
        }else{
            return response('Unauthoraized',403);
        }
    }
    // works
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $todo = Todo::where('owner_id', $user->id)->where('id',$id)->first();

        if($todo){
            Todo::destroy($todo->id);
            return  response('Success',200);
        }else{
            return response('Unauthoraized',403);
        }
    }
}