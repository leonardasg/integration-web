<div class="card">
    <div class="card-header">
        <h6 class="title">@if(!empty($table_name)) {{ $table_name }} @else Tasks @endif </h6>
    </div>
    <div class="card-body">
        <div class="table-full-width table-container">
            <table class="table">
                <tbody>
                @if(!empty($tasks))
                    @if(isset($as_type) && $as_type)

                        @foreach($tasks as $id_type => $type)
                            <tr class="tasks-type" id="tasks-{{$id_type}}" >
                                <td class="text-center" colspan="10">
                                    {{ $type[0]->role_name }}

                                    <button type="button" class="btn btn-link show-more btn-icon" data-toggle="{{$id_type}}">
                                        <i class="tim-icons icon-minimal-down"></i>
                                    </button>

                                    <button type="button" class="btn btn-link show-less btn-icon" data-toggle="{{$id_type}}">
                                        <i class="tim-icons icon-minimal-up"></i>
                                    </button>
                                </td>
                            </tr>
                            @foreach($type as $task)
                                <tr data-id-type="{{$id_type}}">
                                    <td class="checkbox">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input task-checkbox" type="checkbox" value="{{ $task->id_user_point }}"
                                                       @if(!empty($task->finished_at)) checked @endif>
                                                <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="title">{{ $task->name }}
                                            <span class="purple">
                                                @if(!empty($task->points)) ( {{ $task->points }} {{ __('points') }} @if($task->count > 1) x {{ $task->count }} {{ __('times')}} @endif {{ __(')') }} @endif
                                            </span>
                                        </p>
                                        @if(!empty($task->description))
                                            <p class="text-muted">{{ $task->description }}</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach

                    @else

                        @foreach($tasks as $task)
                            <tr>
                                <td class="checkbox">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input task-checkbox" type="checkbox" value="{{ $task->id_user_point }}"
                                                   @if(!empty($task->finished_at)) checked @endif>
                                            <span class="form-check-sign">
                                                <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <p class="title">{{ $task->name }}
                                        <span class="purple">
                                            @if(!empty($task->points)) ( {{ $task->points }} {{ __('points') }} @if($task->count > 1) x {{ $task->count }} {{ __('times')}} @endif {{ __(')') }} @endif
                                        </span>
                                    </p>
                                    @if(!empty($task->description))
                                        <p class="text-muted">{{ $task->description }}</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    @endif
                @else
                    <h6 class="red">{{__('You do not have any')}} {{ !empty($table_name) ? $table_name : 'tasks' }} {{__('yet.')}}</h6>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            custom.hideTasksByType();
        });
    </script>
@endpush


