@extends('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'profile'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('New task') }}</h5>
                </div>
                <form method="post" action="{{ route('task.add') }}" autocomplete="off">
                    <div class="card-body">
                        @csrf
                        @method('put')

                        @include('alerts.success')

                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Create a questionnaire for accreditation') }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label>{{ __('Description') }}</label>
                            <input type="text" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('The questionnaire must not be shorter than 10 questions') }}">
                            @include('alerts.feedback', ['field' => 'description'])
                        </div>

                        <div class="form-group{{ $errors->has('points') ? ' has-danger' : '' }}">
                            <label>{{ __('Points') }}</label>
                            <input type="number" name="points" class="form-control{{ $errors->has('points') ? ' is-invalid' : '' }}" placeholder="{{ __('20') }}">
                            @include('alerts.feedback', ['field' => 'description'])
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
