@extends('layouts.app', ['page' => __('User'), 'pageSlug' => 'user'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ isset($user) ? __('Edit User')  : __('New User') }}</h5>
                </div>
                <form method="post" action="{{ isset($user) ? route('users.update', ['user' => $user]) : route('users.add') }}" autocomplete="off">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        @include('alerts.success')
                        @include('alerts.error', ['key' => 'error'])

                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Vardenis Pavardenis') }}" value="{{ old('name', isset($user) ? $user->name : '') }}">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label>{{ __('Email') }}</label>
                            <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('vardenis.pavardenis@gmail.com') }}" value="{{ old('email', isset($user) ? $user->email : '') }}">
                            @include('alerts.feedback', ['field' => 'email'])
                        </div>

                        <div class="form-group">
                            <label>{{ __('Roles') }}</label>
                            @foreach($roles as $role)
                                <div id="role-checkbox-{{ $role->id }}" class="role-checkbox">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        {{ isset($user) && in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'checked' : '' }}>
                                    <span>{{ $role->name }}</span>
                                </div>
                            @endforeach
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

@push('js')
    <script>
        $(document).ready(function() {
            custom.initMultipleSelection();
        });
    </script>
@endpush
