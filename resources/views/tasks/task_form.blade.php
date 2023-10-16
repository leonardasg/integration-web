@extends('layouts.app', ['page' => __('Task'), 'pageSlug' => 'task'])

@section('content')
    @if(!isset($task) || auth()->user()->canEditTask($task))
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

                            <div class="form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                                <label for="type">{{ __('Type') }}</label>
                                <select name="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                                    @foreach($task_types as $type)
                                        <option value="{{ $type['id'] }}"
                                                @if(isset($task) && $task->type == $type['id'] || isset($selected_type) && $selected_type == $type['id']) selected @endif>
                                            {{ $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @include('alerts.feedback', ['field' => 'type'])
                            </div>

                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label>{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Create a questionnaire for accreditation') }}" value="{{ old('name', isset($task) ? $task->name : '') }}">
                                @include('alerts.feedback', ['field' => 'name'])
                            </div>

                            <div id="description" class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                <label>{{ __('Description') }}</label>
                                <input type="text" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('The questionnaire must not be shorter than 10 questions') }}" value="{{ old('description', isset($task) ? $task->description : '') }}">
                                @include('alerts.feedback', ['field' => 'description'])
                            </div>

                            <div id="points" class="form-group{{ $errors->has('points') ? ' has-danger' : '' }}">
                                <label>{{ __('Points') }}</label>
                                <input type="number" name="points" class="form-control{{ $errors->has('points') ? ' is-invalid' : '' }}" value="{{ old('points', isset($task) ? $task->points : 0) }}">
                                @include('alerts.feedback', ['field' => 'points'])
                            </div>

                            <div id="date_from" class="form-group{{ $errors->has('date_from') ? ' has-danger' : '' }}">
                                <label>{{ __('Date From') }}</label>
                                <input type="datetime-local" name="date_from" class="form-control{{ $errors->has('date_from') ? ' is-invalid' : '' }}" value="{{ old('date_from', isset($task) ? $task->date_from : '') }}">
                                @include('alerts.feedback', ['field' => 'date_from'])
                            </div>

                            <div id="date_to" class="form-group{{ $errors->has('date_to') ? ' has-danger' : '' }}">
                                <label>{{ __('Date To') }}</label>
                                <input type="datetime-local" name="date_to" class="form-control{{ $errors->has('date_to') ? ' is-invalid' : '' }}" value="{{ old('date_to', isset($task) ? $task->date_to : '') }}">
                                @include('alerts.feedback', ['field' => 'date_to'])
                            </div>

                            @if(auth()->user()->isAdmin() || auth()->user()->isZIKCoordinator() || auth()->user()->isCoordinator($task->type ?? null))
                                <div id="active" class="form-group{{ $errors->has('active') ? ' has-danger' : '' }}">
                                    <label for="active">{{ __('Active') }}</label>
                                    <select name="active" class="form-control{{ $errors->has('active') ? ' is-invalid' : '' }}">
                                        <option value="0" @if(isset($task) && !$task->active) selected @endif>False</option>
                                        <option value="1" @if(isset($task) && $task->active) selected @endif>True</option>
                                    </select>
                                    @include('alerts.feedback', ['field' => 'active'])
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="text-center">
            <h1  class="purple">Ką čia darai?</h1>
            <h3 class="purple">Neturi teisės taisyti šitą task'ą!</h3>
        </div>
    @endif
@endsection

@push('js')
    <script>
        window.appConfig = @json([
            'id_quest' => config('custom.QUEST_ID')
        ]);

        $(document).ready(function() {
            custom.initTaskType();
        });
    </script>
@endpush
