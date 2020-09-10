@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Create New Filter Type</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('filter_types.index') }}"> Back</a>
            </div>
        </div>
    </div>


    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('filter_types.store') }}" accept-charset="UTF-8" id="create_filter_type_form" name="create_filter_type_form" class="form-horizontal">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input class="form-control" name="name" type="text" id="name" value="" placeholder="Enter name..." maxlength="100">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Description:</strong>
                    <input class="form-control" name="description" type="text" id="description" value="" placeholder="Enter description..." maxlength="200">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Searchable:</strong>
                    <br>
                    <input class="" name="searchable" type="checkbox" id="searchable" value="true">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <strong>Show Type:</strong>
                    <select class="form-control" id="show_type" name="show_type">
                        <option value="" style="display: none;" disabled selected>Select show type</option>
                        @foreach ($show_types as $key => $show_type)
                        <option value="{{ $key }}" >
                            {{ $show_type }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Color:</strong>
                    <input class="form-control" name="color" type="color" id="color" value="" >
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>


@endsection