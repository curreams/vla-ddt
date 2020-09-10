<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\FilterType;
use App\Models\ShowType;

use DB;

class FilterTypeController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:filter_types-list|filter_types-create|filter_types-edit|filter_types-delete', ['only' => ['index','store']]);
        $this->middleware('permission:filter_types-create', ['only' => ['create','store']]);
        $this->middleware('permission:filter_types-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:filter_types-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = FilterType::orderBy('id','ASC')->paginate(5);
        return view('filter_types.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $show_types =  ShowType::pluck('name','id')->all();
        return view('filter_types.create', compact('show_types'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $filter_type = FilterType::find($id);
        return view('filter_types.show',compact('filter_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $show_types =  ShowType::pluck('name','id')->all();
        $filter_type = FilterType::find($id);
        return view('filter_types.edit',compact('filter_type','show_types'));
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
            'name' => 'required|unique:filter_types,name',
            'description' => 'required',
        ]);
        $input = $request->all();
        if(array_key_exists('searchable',$input)){
            $input['searchable'] = true;
        }else{
            $input['searchable'] = false;
        }
        $filter_type = FilterType::create($input);

        return redirect()->route('filter_types.index')
                        ->with('success','Filter type created successfully');
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
        ]);

        $input = $request->all();


        if(array_key_exists('searchable',$input)){
            $input['searchable'] = true;
        }else{
            $input['searchable'] = false;
        }
        $filter_type = FilterType::find($id);
        $filter->update($input);


        return redirect()->route('filter_types.index')
                        ->with('success','Filter type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("filter_types")->where('id',$id)->delete();
        return redirect()->route('filter_types.index')
                        ->with('success','Filter Type deleted successfully');
    }

}
