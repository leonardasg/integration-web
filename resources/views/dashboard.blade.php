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
            <div class="col-lg-12 col-md-12">
                @include('tasks.freshman_tasks_dashboard')
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 col-lg-6 col-md-6">
                @include('tasks.freshman_quests_dashboard')
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                @include('tasks.levels')
            </div>
        </div>
    @elseif(auth()->user()->isMember())
        <div class="text-center">
            <h1  class="purple">Sveikas, nary!</h1>
            <h3 class="purple">Čia sudėsiu vėliau tavo sukurtus task'us. Dabar viską gali pasiekt per šoninius tab'us!</h3>
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
