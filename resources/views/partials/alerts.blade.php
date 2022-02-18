{{-- These are the session alerts for success and error  --}}

@if(session()->has('success'))
<div class="alert alert-success session-alert fade-in" role="alert">
    {{ session()->get('success') }}
</div>
@endif


@if(session()->has('errors'))
<div class="alert alert-danger session-alert fade-in" role="alert">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>
            {{ $error }}
        </li>
        @endforeach
    </ul>
</div>
@endif
