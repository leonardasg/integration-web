<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="flex-row card-header card-header-primary">
                <h4 class="card-title d-inline">
                    @if(isset($freshman))
                        {{$freshman->user->name}}
                    @endif
                    {{ $table_name ?? 'tasks' }}
                </h4>
            </div>
            <div class="card-body">
                @if(!empty($tasks))
                    @include('alerts.success')
                    <div class="table-container">
                        <table class="table">
                            <thead class=" text-primary">
                            @if(isset($info) && ($info == 'mentoring' || $info == 'other_tasks'))
                                <th>No.</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th class="text-center">Points</th>
                                <th class="text-center date">Assigned</th>
                                <th class="text-center date">Finished</th>
                                <th class="text-center date">Verified</th>
                                <th class="text-center">Count</th>
                            @elseif(isset($info) && $info == 'quests')
                                <th>No.</th>
                                <th>Name</th>
                                <th class="text-center date">Assigned</th>
                                <th class="text-center date">Finished</th>
                                <th class="text-center date">Verified</th>
                            @else
                                <th>No.</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th class="text-center">Points</th>
                                <th>Created by</th>
                                <th class="text-center date">Assigned</th>
                                <th class="text-center date">Finished</th>
                                <th class="text-center date">Verified</th>
                                <th class="text-center">Count</th>
                            @endif
                            </thead>
                            <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    @if(isset($info) && ($info == 'mentoring' || $info == 'other_tasks'))
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{ $task->name }}</td>
                                        <td>{{ $task->description }}</td>
                                        <td class="text-center">{{ $task->points }}</td>
                                        <td>{{ $task->assigned_at }}</td>
                                        <td class="text-center">
                                            @if($task->finished_at)
                                                {{ $task->finished_at }}
                                            @else
                                                <span class="tim-icons red icon-simple-remove"></span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($task->verified_at)
                                                {{ $task->verified_at }}
                                            @else
                                                <span class="tim-icons red icon-simple-remove"></span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $task->count }}</td>
                                    @elseif(isset($info) && $info == 'quests')
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{ $task->name }}</td>
                                        <td>{{ $task->assigned_at }}</td>
                                        <td class="text-center">
                                            @if($task->finished_at)
                                                {{ $task->finished_at }}
                                            @else
                                                <span class="tim-icons red icon-simple-remove"></span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($task->verified_at)
                                                {{ $task->verified_at }}
                                            @else
                                                <span class="tim-icons red icon-simple-remove"></span>
                                            @endif
                                        </td>
                                    @else
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{ $task->name }}</td>
                                        <td>{{ $task->description }}</td>
                                        <td>{{ $task->role_name }}</td>
                                        <td class="text-center">{{ $task->points }}</td>
                                        <td>{{ $task->created_by }}</td>
                                        <td>{{ $task->assigned_at }}</td>
                                        <td class="text-center">
                                            @if($task->finished_at)
                                                {{ $task->finished_at }}
                                            @else
                                                <span class="tim-icons red icon-simple-remove"></span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($task->verified_at)
                                                {{ $task->verified_at }}
                                            @else
                                                <span class="tim-icons red icon-simple-remove"></span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $task->count }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <h6 class="red">{{__('Do not have any')}} {{ !empty($table_name) ? $table_name : 'tasks' }} {{__('yet.')}}</h6>
                @endif
            </div>
        </div>
    </div>
</div>
