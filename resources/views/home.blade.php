@extends('layouts.app')

@section('content')
<div id="app">
    <spiner></spiner>
    <div class="container">
        <div class="row justify-content-center">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <maincomponent  :filter_types="{{$filter_types}}"
                            :sa4="{{$SA4_filters}}"
                            :sa3="{{$SA3_filters}}">
            </maincomponent>
        </div>
    </div>
</div>
    @endsection
@section('scripts')
<script src="/js/main.js?id={{ str_random(6) }}"></script>
@endsection

