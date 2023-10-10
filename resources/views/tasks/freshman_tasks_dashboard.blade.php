<div class="card">
    <div class="card-header">
        <h6 class="title">Tasks</h6>
    </div>
    <div class="card-body">
        <div class="table-full-width">
            <table class="table">
                <tbody>
                @if(!empty($tasks))
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
                                <p class="title">{{ $task->name }}</p>
                                <p class="text-muted">{{ $task->description }}</p>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <h6 class="red">{{__('You do not have any tasks yet.')}}</h6>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


