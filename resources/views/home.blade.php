@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <example-component></example-component>
    </div>
@endsection
@section('scripts')
    <script src="/js/app.js?id={{ str_random(6) }}"></script>
@endsection
