@extends('layouts.app')

@section('content')
    @isset($header)
        <div class="mb-3">
            {!! $header !!}
        </div>
    @endisset

    {{ $slot }}
@endsection
