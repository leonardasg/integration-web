<div class="card">
    <div class="card-header">
        <h6 class="title">Quests</h6>
    </div>
    <div class="card-body">
        <div class="table-full-width">
            <table class="table">
                <tbody>
                @if(!empty($quests))
                    @foreach($quests as $quest)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" value="{{ $quest->id_user_point }}"
                                               @if(!empty($quest->finished_at)) checked @endif>
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <p class="title">{{ $quest->name }}</p>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <h6 class="red">{{__('You do not have any quests yet.')}}</h6>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


