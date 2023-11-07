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
                                @include('tasks.partials.task_edition_dropdown')
                            </td>
                        @endif
                    </tr>
                    @if(!empty($task->assigned_to))
                        <tr>
                            <td colspan="10">
                                @include('tasks.partials.assigned_to')
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
