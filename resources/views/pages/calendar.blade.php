@extends('layouts.app', ['page' => __('Calendar'), 'pageSlug' => 'calendar'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            custom.initCalendar(@json($events));
        });
    </script>
@endpush

