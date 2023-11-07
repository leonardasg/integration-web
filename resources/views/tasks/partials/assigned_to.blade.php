<div class="table-container">
    <table class="table" id="table-{{ $task->id }}">
        <thead>
        <th>Assigned Freshman</th>
        <th class="text-center">Finished</th>
        <th class="text-center">Verified</th>
        <th class="text-center">Count</th>
        </thead>
        <tbody>

        @if(!empty($task->assigned_to))
            <tr>
                <td class="text-left">{{$task->assigned_to[0]->user_name}}</td>
                <td class="text-center">
                    @if($task->assigned_to[0]->finished_at)
                        <span class="tim-icons green icon-check-2"></span>
                    @else
                        <span class="tim-icons red icon-simple-remove"></span>
                    @endif
                </td>
                <td class="text-center">
                    @if($task->assigned_to[0]->verified_at)
                        <span class="tim-icons green icon-check-2"></span>
                    @else
                        <span class="tim-icons red icon-simple-remove"></span>
                    @endif
                </td>
                <td class="text-center">{{$task->assigned_to[0]->count}}</td>

                @if(auth()->user()->canEditTask($task))
                    <td class="text-right">
                        <div class="dropdown">
                            <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                                <i class="tim-icons icon-pencil"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <form method="POST" action="{{ route('task.unassign') }}">
                                    @csrf
                                    @method('put')

                                    <input type="hidden" name="id_user_point"
                                           value={{ $task->assigned_to[0]->id_user_point }}>
                                    <button class="dropdown-item red confirm-form" type="submit"
                                            data-confirm="Are you sure you want to unassign this user?">Unassign
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('task.verify') }}">
                                    @csrf
                                    @method('put')

                                    <input type="hidden" name="id_user_point"
                                           value={{ $task->assigned_to[0]->id_user_point }}>
                                    <button class="dropdown-item green confirm-form" type="submit"
                                            data-confirm="Are you sure you want to verify that this user has completed this task?">
                                        Verify
                                    </button>
                                </form>

                                <button class="dropdown-item green edit-count" type="submit" data-id_user_point="{{ $task->assigned_to[0]->id_user_point }}">
                                    Change count
                                </button>
                            </div>
                        </div>
                    </td>
                @endif
            </tr>
            @if(isset($task->assigned_to[1]))
                <tr class="show-more-less show-more-row">
                    <td class="text-center" colspan="10">
                        <button type="button" class="btn btn-link show-more btn-icon" data-toggle="{{ $task->id }}">
                            <i class="tim-icons icon-minimal-down"></i>
                        </button>
                    </td>
                </tr>

                @for($index = 1; $index < count($task->assigned_to); $index++)
                    <tr class="hide-row" aria-colspan="10">
                        <td class="text-left">{{$task->assigned_to[$index]->user_name}}</td>
                        <td class="text-center">
                            @if($task->assigned_to[$index]->finished_at)
                                <span class="tim-icons green icon-check-2"></span>
                            @else
                                <span class="tim-icons red icon-simple-remove"></span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($task->assigned_to[$index]->verified_at)
                                <span class="tim-icons green icon-check-2"></span>
                            @else
                                <span class="tim-icons red icon-simple-remove"></span>
                            @endif
                        </td>
                        <td class="text-center">{{$task->assigned_to[$index]->count}}</td>

                        @if(auth()->user()->canEditTask($task))
                            <td class="text-right">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-link dropdown-toggle btn-icon"
                                            data-toggle="dropdown">
                                        <i class="tim-icons icon-pencil"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right"
                                         aria-labelledby="dropdownMenuLink">
                                        <form method="POST" action="{{ route('task.unassign') }}">
                                            @csrf
                                            @method('put')

                                            <input type="hidden" name="id_user_point"
                                                   value={{ $task->assigned_to[$index]->id_user_point }}>
                                            <button class="dropdown-item red confirm-form" type="submit"
                                                    data-confirm="Are you sure you want to unassign this user?">
                                                Unassign
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('task.verify') }}">
                                            @csrf
                                            @method('put')

                                            <input type="hidden" name="id_user_point"
                                                   value={{ $task->assigned_to[$index]->id_user_point }}>
                                            <button class="dropdown-item green confirm-form" type="submit"
                                                    data-confirm="Are you sure you want to verify that this user has completed this task?">
                                                Verify
                                            </button>
                                        </form>

                                        <button class="dropdown-item green edit-count" type="submit" data-id_user_point="{{ $task->assigned_to[$index]->id_user_point }}">
                                            Change count
                                        </button>
                                    </div>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endfor

                <tr class="show-more-less show-less-row">
                    <td class="text-center" colspan="10">
                        <button type="button" class="btn btn-link show-less btn-icon" data-toggle="{{ $task->id }}">
                            <i class="tim-icons icon-minimal-up"></i>
                        </button>
                    </td>
                </tr>
            @endif
        @endif
        </tbody>
    </table>
</div>


@push('js')
    <script>
        $(document).ready(function () {
            custom.hideShowTableRows();
            custom.editCount();
        });
    </script>
@endpush
