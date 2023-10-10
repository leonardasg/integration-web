@extends('layouts.app', ['page' => __('Users'), 'pageSlug' => 'users'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="flex-row card-header">
                    <h4 class="card-title">All Users</h4>
                    @if(auth()->user()->hasRole(config('custom.ADMIN')))
                        <div class="dropdown">
                            <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                                <i class="tim-icons icon-settings-gear-63"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a href="{{route('users.create')}}" class="dropdown-item">Add user</a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-body">

                    @include('alerts.success')

                    <div class="table-container">
                        <table class="table tablesorter">
                            <thead class=" text-primary">
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="photo">
                                                <img src=" @if(!empty($user->avatar)) {{ asset('uploads/' . $user->avatar) }} @else {{ asset('black') }}/img/anime3.png @endif" alt="{{ __('Profile Photo') }}">
                                            </div>
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->roles }}</td>
                                        @if(auth()->user()->hasRole(config('custom.ADMIN')))
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                                                        <i class="tim-icons icon-pencil"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                        <a href="{{ route('users.edit', ['user' => $user]) }}" class="dropdown-item">Edit</a>

                                                        <form method="POST" action="{{ route('users.remove') }}">
                                                            @csrf
                                                            @method('put')

                                                            <input type="hidden" name="id_user" value={{ $user->id }}>
                                                            <button class="dropdown-item confirm-form" type="submit" data-confirm="Are you sure you want to remove this user?">Remove</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
