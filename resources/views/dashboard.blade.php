@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
    @if(auth()->user()->isFreshman())
        <div class="row">
            <div class="col-12">
                <div class="card card-chart">
                    <div class="card-header ">
                        <div class="row">
                            <div class="col-sm-6 text-left">
                                <h4 class="card-title">Total Experience</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="horizontalStackedBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 col-lg-6 col-md-6">
                @include('tasks.freshman_tasks_dashboard',  ['tasks' => $tasks, 'as_type' => true, 'table_name' => __('Tasks of committees')])

                @include('tasks.freshman_tasks_dashboard', ['tasks' => $mentor_tasks, 'table_name' => __('Mentoring tasks')])

                @include('tasks.freshman_tasks_dashboard', ['tasks' => $other_tasks, 'table_name' => __('Other tasks')])

                @include('tasks.freshman_tasks_dashboard', ['tasks' => $quests, 'table_name' => __('Quests')])
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                @include('tasks.partials.levels', ['freshman_level' => $freshman->level])
            </div>
        </div>
    @elseif(auth()->user()->isMember())
        @include('tasks.member_tasks_dashboard')
    @elseif(auth()->user()->isAdmin())
        <div class="text-center">
            <h1 class="purple">Hello Site Admin!</h1>
            <h3 class="purple">If you run to some bug, problem or just have some questions -> contact <a href="https://www.facebook.com/GrazulioIgnas/" target="_blank">me</a></h3>
        </div>
    @else
        <div class="text-center">
            <h1 class="purple">Congratulations on your registration!</h1>
            <h3 class="purple">Please wait! Administrator will add you role soon ;)</h3>
        </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>
    <script>
        $(document).ready(function() {
          demo.initDashboardPageCharts();
          custom.finishTask();
        });
    </script>
@endpush
