@extends('layouts.app')

@section('content')
<div id="app">
    <div class="container">
        <div class="row justify-content-center">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <filters :filter_types="{{$filter_types}}"></filters>
            <barchar></barchar>
        </div>
    </div>
</div>
    @endsection
@section('scripts')
<script src="/js/main.js?id={{ str_random(6) }}"></script>
@endsection

