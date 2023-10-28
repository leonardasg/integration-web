@extends('layouts.app', ['page' => __('Freshman tasks List'), 'pageSlug' => 'freshman_tasks'])
@section('content')
    <div class="content">
        @include('tasks.freshman_tasks_list', ['table_name' => 'tasks of committees'])
        @include('tasks.freshman_tasks_list', ['info' => 'mentoring','tasks' => $mentor_tasks, 'table_name' => 'mentoring tasks'])
        @include('tasks.freshman_tasks_list', ['info' => 'quests', 'tasks' => $quests, 'table_name' => 'quests'])
    </div>
@endsection
