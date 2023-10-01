@extends('layouts.app', ['page' => __('Roles'), 'pageSlug' => 'roles'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-tasks">
                <div class="card-header">
                    <h4 class="card-title d-inline">All Roles</h4>
                    @if(auth()->user()->hasRole('admin'))
                        <div class="dropdown">
                            <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                                <i class="tim-icons icon-settings-gear-63"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a href="{{route('role.create')}}" class="dropdown-item">Add role</a>
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
                                    As Type
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>@if($role->as_type) <span class="tim-icons green icon-check-2"></span> @else <span class="tim-icons red icon-simple-remove"></span> @endif</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                                                    <i class="tim-icons icon-pencil"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                    <a href="{{ route('role.edit', ['role' => $role]) }}" class="dropdown-item">Edit</a>

                                                    <form method="POST" action="{{ route('role.remove') }}">
                                                        @csrf
                                                        @method('put')

                                                        <input type="hidden" name="id_role" value={{ $role->id }}>
                                                        <button class="dropdown-item" type="submit">Remove</button>
                                                    </form>
                                                </div>
                                            </div>
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
@endsection
