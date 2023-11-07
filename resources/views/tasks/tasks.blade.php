@extends('layouts.app', ['page' => __('Tasks List'), 'pageSlug' => 'tasks'])

@section('content')
    <div id="all-tasks" class="row">
        <div class="col-md-12">
            @include('tasks.tasks_list')

            @include('tasks.quests_list')
        </div>
    </div>

    @include('tasks.modals.assign')
    @include('tasks.modals.verify')
@endsection


