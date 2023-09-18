@extends('layouts.app', ['page' => __('Users'), 'pageSlug' => 'users'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">All Users</h4>
                </div>
                <div class="card-body">

                    @include('alerts.success')
                    @include('alerts.error', ['key' => 'error'])

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
                                @if(auth()->user()->hasRole('admin'))
                                    <th class="font-italic">
                                        Actions
                                    </th>
                                @endif
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
                                                <table class="internal-table">
                                                    <tr>
                                                        <td class="actions-col">
                                                            <a href="{{ route('users.edit', ['user' => $user]) }}">edit</a>
                                                        </td>
                                                    </tr>
{{--                                                    <tr>--}}
{{--                                                        <td class="actions-col">--}}
{{--                                                            <form method="POST" action="{{ route('user.remove') }}">--}}
{{--                                                                @csrf--}}
{{--                                                                @method('put')--}}

{{--                                                                <input type="hidden" name="id_task" value={{ $user->id }}>--}}
{{--                                                                <button type="submit">remove</button>--}}
{{--                                                            </form>--}}
{{--                                                        </td>--}}
{{--                                                    </tr>--}}
                                                </table>
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
