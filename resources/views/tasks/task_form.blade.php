@extends('layouts.app', ['page' => __('Task'), 'pageSlug' => 'task'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ isset($task) ? __('Edit Task')  : __('New Task') }}</h5>
                </div>
                <form method="post" action="{{ isset($task) ? route('task.update', ['task' => $task]) : route('task.add') }}" autocomplete="off">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        @include('alerts.success')
                        @include('alerts.error', ['key' => 'error'])

                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Create a questionnaire for accreditation') }}" value="{{ old('name', isset($task) ? $task->name : '') }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                            <label>{{ __('Description') }}</label>
                            <input type="text" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('The questionnaire must not be shorter than 10 questions') }}" value="{{ old('description', isset($task) ? $task->description : '') }}">
                            @include('alerts.feedback', ['field' => 'description'])
                        </div>

                        <div class="form-group{{ $errors->has('points') ? ' has-danger' : '' }}">
                            <label>{{ __('Points') }}</label>
                            <input type="number" name="points" class="form-control{{ $errors->has('points') ? ' is-invalid' : '' }}" placeholder="{{ __('20') }}" value="{{ old('points', isset($task) ? $task->points : '') }}">
                            @include('alerts.feedback', ['field' => 'points'])
                        </div>
                    </div>
                    <div class="card-footer">g
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
