<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="flex-row card-header card-header-primary">
                <h4 class="card-title d-inline">
                    @if(isset($freshman))
                        {{$freshman->user->name}}
                    @endif Quests List
                </h4>
            </div>
            <div class="card-body">
                @include('alerts.success')
                <div class="table-container">
                    <table class="table">
                        <thead class=" text-primary">
                        <th>
                            No.
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Assigned
                        </th>
                        <th class="text-center">
                            Finished
                        </th>
                        <th class="text-center">
                            Verified
                        </th>
                        </thead>
                        <tbody>

                        @foreach($quests as $task)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->assigned_at }}</td>
                                <td class="text-center">
                                    @if($task->finished_at)
                                        <span class="tim-icons green icon-check-2"></span>
                                    @else
                                        <span class="tim-icons red icon-simple-remove"></span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($task->verified_at)
                                        <span class="tim-icons green icon-check-2"></span>
                                    @else
                                        <span class="tim-icons red icon-simple-remove"></span>
                                    @endif
                                </td>
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
    </div>
</div>

@include('tasks.assign')
