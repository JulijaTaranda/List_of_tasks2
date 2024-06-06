@extends('layouts.app')

@section('title', 'The list of tasks')
@section('content')
    <nav class="mb-4 text-m">
        <a href="{{ route('tasks.create') }}" class="link">Add Task</a>
    </nav>
    <div>
        @forelse($tasks as $task)
            <div>
                <!--такой код был до того, как в пути сделали поиск через модель, а не по id
                    <li><a href=" //route('tasks.show', ['id' => $task ->id])}}"> //$task->title }}</a></li>
                   -->
                <a href="{{ route('tasks.show', ['task' => $task->id]) }}"
                    @class([/*'font-bold',*/ 'line-through' => $task->completed])>{{ $task->title }}</a>
            </div>
        @empty
            <div>There are no tasks!</div>
        @endforelse

        @if ($tasks->count())
            <nav class="mt-4">
                {{ $tasks->links() }}
            </nav>
        @endif
    </div>
@endsection
