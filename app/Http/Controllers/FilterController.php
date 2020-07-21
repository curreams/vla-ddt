<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\FilterType;
use Illuminate\Http\Request;

class FilterController extends Controller
{
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Filter::orderBy('id','DESC')->with('parent')->with('filterType')->paginate(5);
        return view('filters.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $filter_types = FilterType::pluck('name','id')->all();
        $filter_parents = Filter::whereNull('parent_id')->get()->pluck('name','id')->all();
        return view('filters.create',compact('filter_types', 'filter_parents'));
    }

/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required'
        ]);

        $input = $request->all();
        $filter = Filter::create($input);

        return redirect()->route('filters.index')
                        ->with('success','Filter created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //->with('parent')->with('filterType')
        $filter = Filter::find($id);
        return view('filters.show',compact('filter'));
    }

        /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $filter = Filter::find($id);
        $filter_types = FilterType::pluck('name','id')->all();
        $filter_parents = Filter::whereNull('parent_id')->get()->pluck('name','id')->all();
        return view('filters.edit',compact('filter','filter_types','filter_parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'type' => 'required'
        ]);
        $input = $request->all();
        dump($input);
        $filter = Filter::find($id);
        $filter->update($input);
        dd($filter);

        return redirect()->route('filters.index')
                        ->with('success','Filter updated successfully');
    }

}
