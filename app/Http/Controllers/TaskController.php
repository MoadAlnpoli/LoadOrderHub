<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdatwTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    function index(){
$task=Task::all();
return response()->json($task,200);
 }



    function store(StoreTaskRequest $request){
$task=Task::create($request->validated());
return response()->json($task,201);
    }



   function update(UpdatwTaskRequest $request,$id){
 $task=Task::findorfail($id);

$task->update($request->validated());
return response()->json($task,200);
    }


function show($id){
 $task=Task::findorfill($id);
return response()->json($task,200);
    }


    function destroy($id){
 $task=Task::findorfail($id);
 $task->delete();
return response()->json(null,204);
    }
}
