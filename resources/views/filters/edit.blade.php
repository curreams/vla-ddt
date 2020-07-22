@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Filter</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('filters.index') }}"> Back</a>
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


    <form method="POST" action="{{ route('filters.update', $filter->id) }}" id="edit_filter_form" name="edit_filter_form" accept-charset="UTF-8" class="form-horizontal">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">
        <input id='id' type='hidden' value="{{ old('id', optional($filter)->id) }}">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($filter)->name) }}" placeholder="Enter name..." maxlength="100">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Description:</strong>
                    <input class="form-control" name="description" type="text" id="description" value="{{ old('description', optional($filter)->description) }}" placeholder="Enter description..." maxlength="200">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Parent:</strong>
                    <select class="form-control" id="parent_id" name="parent_id">
                        <option value="" style="display: none;" {{ old('parent_id', optional($filter)->parent_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select parent</option>
                        @foreach ($filter_parents as $key => $filter_parent)
                        <option value="{{ $key }}" {{ (old('parent_id', optional($filter)->parent_id) == $key ) ? 'selected' : '' }}>
                            {{ $filter_parent }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Filter Type:</strong>
                    <select class="form-control" id="type" name="type">
                        <option value="" style="display: none;" {{ old('type', optional($filter)->parent_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select type</option>
                        @foreach ($filter_types as $key => $filter_type)
                        <option value="{{ $key }}" {{ (old('type', optional($filter)->type) == $key ) ? 'selected' : '' }}>
                            {{ $filter_type }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Parameter Name:</strong>
                    <input class="form-control" name="parameter_name" type="text" id="parameter_name" value="{{ old('parameter_name', optional($filter)->parameter_name) }}" placeholder="Enter parameter name..." maxlength="200">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>

@endsection