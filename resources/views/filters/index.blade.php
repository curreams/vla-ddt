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
        <td>{{ $filter->value }}</td>
        <td>
            <a class="btn btn-info" href="{{ route('filters.show',$filter->id) }}">Show</a>
            <a class="btn btn-primary" href="{{ route('filters.edit',$filter->id) }}">Edit</a>
            <form method="POST" action="{{ route('filters.destroy', $filter->id) }}" id="destroy_filter_form" name="destroy_filter_form" accept-charset="UTF-8" style="display:inline">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="DELETE">
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
    </table>

    {!! $data->render() !!}
</div>

@endsection