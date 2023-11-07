<div class="dropdown">
    <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
        <i class="tim-icons icon-pencil"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
        <a href="{{ route('task.edit', ['id_task' => $task->id]) }}" class="dropdown-item">Edit</a>

        <form method="POST" action="{{ route('task.remove') }}">
            @csrf
            @method('put')

            <input type="hidden" name="id_task" value={{ $task->id }}>
            <button class="dropdown-item red confirm-form" type="submit"
                    data-confirm="Are you sure you want to remove this task?">Remove
            </button>
        </form>
        <a class="dropdown-item green" href="#" data-toggle="modal" data-target="#assign"
           data-task="{{$task->id}}">Assign freshmen</a>
        @if($task->active)
            <a class="dropdown-item green" href="#" data-toggle="modal" data-target="#verify"
               data-task="{{$task->id}}">Verify freshmen</a>
        @endif
    </div>
</div>
