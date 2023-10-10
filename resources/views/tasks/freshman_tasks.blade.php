@extends('layouts.app', ['page' => __('Freshman tasks List'), 'pageSlug' => 'freshman_tasks'])
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="flex-row card-header card-header-primary">
                        <h4 class="card-title d-inline">
                            @if(isset($freshman))
                                {{$freshman->user->name}}
                            @endif Tasks List
                        </h4>
                    </div>
                    <div class="card-body">
                        @include('alerts.success')
                        <div class="table-container">
                            <table class="table">
                                <thead class=" text-primary">
                                <th>No.</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Points</th>
                                <th>Created by</th>
                                <th>Assigned</th>
                                <th>Finished</th>
                                <th>Verified</th>
                                </thead>
                                <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{ $task->name }}</td>
                                        <td>{{ $task->description }}</td>
                                        <td>{{ $task->role_name }}</td>
                                        <td class="text-center">{{ $task->points }}</td>
                                        <td>{{ $task->created_by }}</td>
                                        <td>{{ $task->assigned_at }}</td>
                                        <td class="text-center">
                                            @if($task->finished_at)
                                                <span class="tim-icons green icon-check-2"></span>
                                            @else
                                                <span class="tim-icons red icon-simple-remove"></span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($task->verified_at)
                                                <span class="tim-icons green icon-check-2"></span>
                                            @else
                                                <span class="tim-icons red icon-simple-remove"></span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('tasks.freshman_quests')
    </div>
@endsection
