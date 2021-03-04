@extends('layouts.app')

@section('content')
<div id="nlas">
    <spiner></spiner>
    <div class="container">
        <div class="row justify-content-center">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif
            <nlasmap :map_data="{{$map_data}}">
            </nlasmap>
        </div>
    </div>
</div>
    @endsection
@section('scripts')
<script src="/js/nlas.js?id={{ str_random(6) }}"></script>
@endsection

