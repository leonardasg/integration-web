@extends('layouts.app', ['page' => __('Task List'), 'pageSlug' => 'tasks'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Tasks List</h4>
                            <p class="card-category">All created tasks</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class=" text-primary">
                                    <th>
                                        ID
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Description
                                    </th>
                                    <th>
                                        Points
                                    </th>
                                    <th>
                                        Created by
                                    </th>
                                    </thead>
                                    <tbody>

                                    @foreach($tasks as $task)
                                        @if (auth()->user()->getAuthIdentifier() == $task->created_by)
                                            <a href="">
                                                <tr>
                                                    <td>{{ $task->id }}</td>
                                                    <td>
                                                        <a href="{{ route('task.edit', ['task' => $task]) }}">{{ $task->name }}</a>
                                                    </td>
                                                    <td>{{ $task->description }}</td>
                                                    <td>{{ $task->points }}</td>
                                                    <td>You</td>
                                                </tr>
                                            </a>
                                        @else
                                            <tr>
                                                <td>{{ $task->id }}</td>
                                                <td>{{ $task->name }}</td>
                                                <td>{{ $task->description }}</td>
                                                <td>{{ $task->points }}</td>
                                                <td>{{ $task->author_name }}</td>
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
        </div>
    </div>
@endsection
