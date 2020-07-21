@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Filters Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('filters.create') }}"> Create New Filter</a>
            </div>
        </div>
    </div>


@if ($message = Session::get('success'))

    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>

@endif


    <table class="table table-bordered">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Description</th>
            <th>Parent</th>
            <th>Type</th>
            <th>Parameter Name</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($data as $key => $filter)
    <tr>
        <td>{{ $filter->id }}</td>
        <td>{{ $filter->name }}</td>
        <td>{{ $filter->description }}</td>
        <td>{{ optional($filter->parent)->name }}</td>
        <td>{{ $filter->filterType->name }}</td>
        <td>{{ $filter->parameter_name }}</td>
        <td>
            <a class="btn btn-info" href="{{ route('filters.show',$filter->id) }}">Show</a>
            <a class="btn btn-primary" href="{{ route('filters.edit',$filter->id) }}">Edit</a>
            {!! Form::open(['method' => 'DELETE','route' => ['filters.destroy', $filter->id],'style'=>'display:inline']) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
        </td>
    </tr>
    @endforeach
    </table>
</div>

{!! $data->render() !!}

@endsection