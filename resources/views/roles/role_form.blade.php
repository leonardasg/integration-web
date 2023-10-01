@extends('layouts.app', ['page' => __('Role'), 'pageSlug' => 'role'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ isset($role) ? __('Edit Role')  : __('New Role') }}</h5>
                </div>
                <form method="post" action="{{ isset($role) ? route('role.update', ['role' => $role]) : route('role.add') }}" autocomplete="off">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        @include('alerts.success')

                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Admin') }}" value="{{ old('name', isset($role) ? $role->name : '') }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group{{ $errors->has('as_type') ? ' has-danger' : '' }}">
                            <label for="as_type">{{ __('As Type') }}</label>
                            <select name="as_type" class="form-control{{ $errors->has('as_type') ? ' is-invalid' : '' }}">
                                <option value="0">False</option>
                                <option value="1">True</option>
                            </select>
                            @include('alerts.feedback', ['field' => 'type'])
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
