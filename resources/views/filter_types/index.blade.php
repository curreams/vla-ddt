@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Filter Types Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('filter_types.create') }}"> Create New Filter Type</a>
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
            <th width="280px">Action</th>
        </tr>
        @foreach ($data as $key => $type)
    <tr>
        <td>{{ $type->id }}</td>
        <td>{{ $type->name }}</td>
        <td>{{ $type->description }}</td>
        <td>
            <a class="btn btn-info" href="{{ route('filter_types.show',$type->id) }}">Show</a>
            <a class="btn btn-primary" href="{{ route('filter_types.edit',$type->id) }}">Edit</a>
            {!! Form::open(['method' => 'DELETE','route' => ['filter_types.destroy', $type->id],'style'=>'display:inline']) !!}
                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
            {!! Form::close() !!}
        </td>
    </tr>
    @endforeach
    </table>
</div>

{!! $data->render() !!}

@endsection