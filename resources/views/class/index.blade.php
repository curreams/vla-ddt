@extends('layouts.app')


@section('content')
<div id="classTable"class="container">

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Class Dimensions Management</h2>
                </div>
            </div>
        </div>


    @if ($message = Session::get('success'))

        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <class-management-table :class_dimensions="{{ json_encode($class_dimensions) }}" :filter_types="{{$filter_types}}"></class-management-table>


</div>

@endsection

@section('scripts')
<script src="/js/class_management_table.js?id={{ str_random(6) }}"></script>
@endsection
