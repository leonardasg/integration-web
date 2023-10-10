<div class="table-container">
    <table class="table">
        <thead>
        <th>Assigned Freshman</th>
        <th class="text-center">Finished</th>
        <th class="text-center">Verified</th>
        </thead>
        <tbody>
        @foreach($task->assigned_to as $user_point)
            <tr>
                <td class="text-left">{{$user_point->user_name}}</td>
                <td class="text-center">
                    @if($user_point->finished_at)
                        <span class="tim-icons green icon-check-2"></span>
                    @else
                        <span class="tim-icons red icon-simple-remove"></span>
                    @endif
                </td>
                <td class="text-center">
                    @if($user_point->verified_at)
                        <span class="tim-icons green icon-check-2"></span>
                    @else
                        <span class="tim-icons red icon-simple-remove"></span>
                    @endif
                </td>

                @if(auth()->user()->getAuthIdentifier() == $task->created_by || auth()->user()->hasRole(config('custom.ADMIN')) || $task->type == config('custom.QUEST_ID'))
                    <td class="text-right">
                        <div class="dropdown">
                            <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                                <i class="tim-icons icon-pencil"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <form method="POST" action="{{ route('task.unassign') }}">
                                    @csrf
                                    @method('put')

                                    <input type="hidden" name="id_user_point" value={{ $user_point->id_user_point }}>
                                    <button class="dropdown-item red" type="submit">Unassign</button>
                                </form>

                                <form method="POST" action="{{ route('task.verify') }}">
                                    @csrf
                                    @method('put')

                                    <input type="hidden" name="id_user_point" value={{ $user_point->id_user_point }}>
                                    <button class="dropdown-item green" type="submit">Verify</button>
                                </form>
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
