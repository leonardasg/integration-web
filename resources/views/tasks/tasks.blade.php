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

                            @include('alerts.success')
                            @include('alerts.error', ['key' => 'error'])

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
                                    <th class="font-italic">
                                        Actions
                                    </th>
                                    </thead>
                                    <tbody>

                                    @foreach($tasks as $task)
                                        @if (auth()->user()->getAuthIdentifier() == $task->created_by)
                                            <a href="">
                                                <tr>
                                                    <td>{{ $task->id }}</td>
                                                    <td>{{ $task->name }}</td>
                                                    <td>{{ $task->description }}</td>
                                                    <td>{{ $task->points }}</td>
                                                    <td>You</td>
                                                    <td>
                                                        <table class="internal-table">
                                                                <tr>
                                                                    <td class="actions-col">
                                                                        <a href="{{ route('task.edit', ['task' => $task]) }}">edit</a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="actions-col">
                                                                        <form method="POST" action="{{ route('task.remove') }}">
                                                                            @csrf
                                                                            @method('put')

                                                                            <input type="hidden" name="id_task" value={{ $task->id }}>
                                                                            <button type="submit">remove</button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </a>
                                        @else
                                            <tr>
                                                <td>{{ $task->id }}</td>
                                                <td>{{ $task->name }}</td>
                                                <td>{{ $task->description }}</td>
                                                <td>{{ $task->points }}</td>
                                                <td>{{ $task->author_name }}</td>
                                                <td class="actions-col">-</td>
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
