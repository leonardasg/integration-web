@extends('layouts.app', ['page' => __('Tasks created by you'), 'pageSlug' => 'created_tasks'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="flex-row card-header card-header-primary">
                    <h4 class="card-title d-inline">Tasks created by you</h4>
                    <div class="dropdown">
                        <button type="button" class="btn btn-link dropdown-toggle btn-icon"
                                data-toggle="dropdown">
                            <i class="tim-icons icon-settings-gear-63"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <a href="{{route('task.create')}}" class="dropdown-item green">Add task</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(!empty($tasks))
                        @include('alerts.success')
                        <div class="table-container">
                            <table class="table">
                                <thead class=" text-primary">
                                <th>No.</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Points</th>
                                <th>Date From</th>
                                <th>Date To</th>
                                <th>Active</th>
                                <th>Created by</th>
                                </thead>
                                <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{ $task->name }}</td>
                                        <td>{{ $task->description }}</td>
                                        <td>{{ $task->role_name }}</td>
                                        <td class="text-center">{{ $task->points }}</td>
                                        <td>
                                            @if(isset($task->date_from))
                                                {{$task->date_from}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($task->date_to))
                                                {{$task->date_to}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->active)
                                                <span class="tim-icons green icon-check-2"></span>
                                            @else
                                                <span class="tim-icons red icon-simple-remove"></span>
                                            @endif
                                        </td>
                                        <td class="purple">You</td>
                                        <td class="text-right">
                                            @include('tasks.partials.task_edition_dropdown')
                                        </td>
                                    </tr>
                                    @if(!empty($task->assigned_to))
                                        <tr>
                                            <td colspan="10">
                                                @include('tasks.partials.assigned_to')
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>


                        </div>
                    @else
                        <h6 class="red">{{__('You do not have any tasks yet.')}}</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('tasks.modals.assign')
    @include('tasks.modals.verify')
@endsection
