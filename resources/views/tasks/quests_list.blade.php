<div class="card">
    <div class="flex-row card-header">
        <h4 class="card-title">Quests List</h4>
        <div class="dropdown">
            <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                <i class="tim-icons icon-settings-gear-63"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                <a href="{{route('task.create', ['selected_type' => config('custom.QUEST_ID')])}}"
                   class="dropdown-item green">Add quest</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @include('alerts.success', ['key' => 'quest_status'])
        <div id="quests" class="table-container">
            <table class="table tablesorter">
                <thead class="text-primary">
                <th>No.</th>
                <th>Name</th>
                </thead>
                <tbody>
                @foreach($quests as $task)
                    <tr>
                        <td>{{$loop->index + 1}}</td>
                        <td>{{ $task->name }}</td>

                        @if(auth()->user()->isMember() || auth()->user()->isAdmin())
                            <td class="text-right">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-link dropdown-toggle btn-icon"
                                            data-toggle="dropdown">
                                        <i class="tim-icons icon-pencil"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                        <a href="{{ route('task.edit', ['task' => $task]) }}"
                                           class="dropdown-item">Edit</a>

                                        <form method="POST" action="{{ route('task.remove') }}">
                                            @csrf
                                            @method('put')

                                            <input type="hidden" name="id_task" value={{ $task->id }}>
                                            <button class="dropdown-item red confirm-form" type="submit"
                                                    data-confirm="Are you sure you want to remove this quest?">Remove
                                            </button>
                                        </form>

                                        <a class="dropdown-item assign-freshman green" href="#" data-toggle="modal"
                                           data-target="#assign" data-task="{{$task->id}}">Assign freshman</a>
                                    </div>
                                </div>
                            </td>
                        @endif
                    </tr>
                    @if(!empty($task->assigned_to))
                        <tr>
                            <td colspan="10">
                                @include('tasks.assigned_to')
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>