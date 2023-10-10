@extends('layouts.app', ['page' => __('Tasks List'), 'pageSlug' => 'tasks'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="flex-row card-header card-header-primary">
                            <h4 class="card-title d-inline">Tasks List</h4>
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
                            @include('alerts.success')
                            <div>
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
                                            <td>{{ $task->role->name }}</td>
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
                                            <td class="@if (auth()->user()->getAuthIdentifier() == $task->created_by) purple @endif">{{ $task->user->name }}</td>
                                            @if (auth()->user()->getAuthIdentifier() == $task->created_by || auth()->user()->hasRole(config('custom.ADMIN')))
                                                <td class="text-right">
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                                                            <i class="tim-icons icon-pencil"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                            <a href="{{ route('task.edit', ['task' => $task]) }}" class="dropdown-item">Edit</a>

                                                            <form method="POST" action="{{ route('task.remove') }}">
                                                                @csrf
                                                                @method('put')

                                                                <input type="hidden" name="id_task" value={{ $task->id }}>
                                                                <button class="dropdown-item red" type="submit">Remove</button>
                                                            </form>
                                                            @if($task->active)
                                                                <a class="dropdown-item green" href="#" data-toggle="modal" data-target="#assign" data-task="{{$task->id}}">Assign freshman</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                        @if(!empty($task->assigned_to))
                                            <tr>
                                                <td colspan="10">
                                                    @include('tasks.assigned_to')
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('tasks.quests')
        </div>
    </div>
@endsection
