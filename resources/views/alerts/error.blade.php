@if (session($key ?? 'status'))
    <div class="alert alert-danger" role="alert">
        {{ session($key ?? 'status') }}
    </div>
@endif
