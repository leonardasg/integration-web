@extends('layouts.app', ['page' => __('Freshmen'), 'pageSlug' => 'freshmen'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="flex-row card-header">
                    <h4 class="card-title">All Freshmen</h4>
                </div>
                <div class="card-body">

                    @include('alerts.success')

                    <div class="table-container">
                        <table class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th></th>
                                    <th>
                                        Name
                                    </th>
                                    <th class="text-center">
                                        Points
                                    </th>
                                    <th class="text-center">
                                        Level
                                    </th>
                                    <th class="text-right">
                                        More
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($freshmen as $freshman)
                                    <tr>
                                        <td>
                                            <div class="photo">
                                                <img src=" @if(!empty($freshman->user->avatar)) {{ asset('uploads/' . $freshman->user->avatar) }} @else {{ asset('black') }}/img/anime3.png @endif" alt="{{ __('Profile Photo') }}">
                                            </div>
                                        </td>
                                        <td>{{ $freshman->user->name }}</td>
                                        <td class="text-center">{{ $freshman->points }}</td>
                                        <td class="text-center">{{ $freshman->level }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                                                    <i class="tim-icons icon-notes"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                    <a href="{{ route('freshman.freshman_tasks', ['id_user' => $freshman->user->id]) }}" class="dropdown-item">See freshman tasks</a>
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
