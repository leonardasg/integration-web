<div class="modal fade" id="assign" tabindex="-1" role="dialog" aria-labelledby="assign-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assign-label">Assign freshman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('task.assign') }}" autocomplete="off">
                <div class="modal-body">
                    @csrf
                    @method('put')

                    <div class="form-group{{ $errors->has('task') ? ' has-danger' : '' }}">
                        <label for="task">{{ __('Task') }}</label>
                        <select name="task" class="form-control{{ $errors->has('task') ? ' is-invalid' : '' }}">
                            <optgroup label="Tasks">
                                @foreach($tasks as $task)
                                    <option value="{{ $task->id }}">
                                        {{ $task->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                            @if(!empty($quests))
                                <optgroup label="Quests">
                                    @foreach($quests as $task)
                                        <option value="{{ $task->id }}">
                                            {{ $task->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                        @include('alerts.feedback', ['field' => 'task'])
                    </div>

                    <div class="form-group{{ $errors->has('freshman') ? ' has-danger' : '' }}">
                        <label>{{ __('Freshman') }}</label>
                            <div id="freshman-checkbox-all" class="freshman-checkbox">
                                <input type="checkbox" name="freshman[]" value="all">
                                <span>All</span>
                            </div>
                        @foreach($freshmen as $freshman)
                            <div id="freshman-checkbox-{{ $freshman->user->id }}" class="freshman-checkbox">
                                <input type="checkbox" name="freshman[]" value="{{ $freshman->user->id }}">
                                <span>{{ $freshman->user->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-fill btn-primary">{{ __('Assign') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            custom.selectAssignTask();
            custom.initMultipleSelection();
        });
    </script>
@endpush
