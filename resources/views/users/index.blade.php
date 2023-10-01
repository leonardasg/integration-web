@extends('layouts.app', ['page' => __('Users'), 'pageSlug' => 'users'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="flex-row card-header">
                    <h4 class="card-title">All Users</h4>
                    @if(auth()->user()->hasRole('admin'))
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

                    <div class="table-responsive">
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Roles
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->roles }}</td>
                                        @if(auth()->user()->hasRole('admin'))
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
                                                            <button class="dropdown-item" type="submit">Remove</button>
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
