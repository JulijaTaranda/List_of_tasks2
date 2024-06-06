<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;

//Main page, redirect to show all tasks
Route::get('/', function () {
    return redirect()->route('tasks.index');
});

//All Tasks
Route::get('/tasks', function () {
    return view('index', [
        //'tasks' => Task::latest()->where('completed',true)->get() //shows only completed tasks
        //'tasks' => Task::latest()->get()
        'tasks' => Task::latest()->paginate(10)
    ]);
})->name('tasks.index');

//Task create
Route::view('/tasks/create', 'create')->name('tasks.create');

// Route for edit form
//Route::get('/tasks/{id}/edit', function ($id) //if we use {id}
Route::get('/tasks/{task}/edit', function (Task $task) {
    return view('edit', [
        'task' => $task //if we use Model Binding, model-Task
        //'task' => Task::findOrFail($id) //if we use id
    ]);
})->name('tasks.edit');

//Single Task
//Route::get('/tasks/{id}', function ($id) {
Route::get('/tasks/{task}', function (Task $task) {
    return view('show', [
        //$task = Task::findOrFail($id); //если по id искать
        'task' => $task
    ]);
})->name('tasks.show');


//Task store
Route::post('/tasks', function (TaskRequest $request) {
    /* когда вместо TaskRequest был Request просто. Request with validation, TaskRequest we make in terminal and copy this rules there. 
    It is working, when we have something similar. Lika here validation in store and edit. DRY
    $data = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'long_description' => 'required',
    ]);
    */
    /* We will make it more simple
    $data = $request->validated();
    $task = new Task; //create new Model and add all data
    $task -> title = $data['title'];
    $task -> description = $data['description'];
    $task -> long_description = $data['long_description'];
    $task->save();
    */

    $task = Task::create($request->validated());

    return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task created successfully!'); //нужно для сообщения, которое мы потом сделаем, котороые всплывёт один раз
    //dd($request->all());
    //dd('We have reached the store route'); было до того, как добавили Request
})->name('tasks.store');


//Update Task
//Route::put('/tasks/{id}', function ($id, Request $request)
Route::put('/tasks/{task}', function (Task $task, TaskRequest $request) {
    /*
    $data = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'long_description' => 'required',
    ]);
    */
    
    /*$data = $request->validated();

    //$task = Task::findOrFail($id); //fetch Task from db and then, modifying it
    $task -> title = $data['title'];
    $task -> description = $data['description'];
    $task -> long_description = $data['long_description'];
    $task->save();
    */

    $task->update($request->validated());

    return redirect()->route('tasks.show', ['task' => $task->id])->with('success', 'Task updated successfully!'); //нужно для сообщения, которое мы потом сделаем, котороые всплывёт один раз
    //dd($request->all());
    //dd('We have reached the store route'); было до того, как добавили Request
})->name('tasks.update');

//Delete Data
Route::delete('/tasks/{task}', function (Task $task) {
    //(Task $task -> fetch the task from db)
    $task->delete();
    return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
})->name('tasks.destroy');

//Toggling
Route::put('/tasks/{task}/toggle-complete', function(Task $task) {
    $task->toggleComplete();
    return redirect()->back()->with('success', 'Task updated successfully!'); //go back to the last page
})->name('tasks.toggle-complete');

