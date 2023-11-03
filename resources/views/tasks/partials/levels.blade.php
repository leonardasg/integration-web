<div class="card">
    <div class="card-header">
        <h6 class="title">Levels</h6>
    </div>
    <div class="card-body">
        <div class="table-full-width table-container">
            <table class="table">
                <tbody>
                @if(!empty($levels))
                    @foreach($levels as $level)
                        <tr>
                            <td class="text-center @if($level->id <= $freshman_level) purple @endif">
                                {{ $level->id }}
                            </td>
                            <td class="@if($level->id <= $freshman_level) purple @endif">
                                {{ $level->privilege }}
                            </td>
                            <td class="text-center @if($level->id <= $freshman_level) purple @endif">
                                {{ $level->points }}@if($level->locked)<i class="tim-icons icon-lock-circle" style="color:darkgoldenrod;display:block"></i>@endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <h6 class="red">{{__('You do not have permissions to see levels yet.')}}</h6>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


