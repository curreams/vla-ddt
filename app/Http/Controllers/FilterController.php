<?php

namespace App\Http\Controllers;

use App\Models\Filter;
use App\Models\FilterType;
use App\Models\SARelation;


use Illuminate\Http\Request;
use DB;

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
            'filter_type' => 'required'
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
        $filter = Filter::find($id);
        $filter->update($input);

        return redirect()->route('filters.index')
                        ->with('success','Filter updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("filters")->where('id',$id)->delete();
        return redirect()->route('filters.index')
                        ->with('success','Filter deleted successfully');
    }

    public function  createFilters()
    {
        $age_groups= AgeGroup::all();
        foreach ($age_group as $key => $age_group) {
            $filter = [

            ];
            $filter = Filter::create($input);
        }
    }

    public function filterSA2(Request $request)
    {
        $SAfilters = $request->all();
        $SA4 = [];
        $SA3 = [];
        foreach ($SAfilters as $key => $filter) {
            if(strcmp($filter['table_header'], 'SA4')==0){
                $SA4[] = $filter['surrogate_key'];
            }
            if(strcmp($filter['table_header'], 'SA3')==0){
                $SA3[] = $filter['surrogate_key'];
            }
        }
        $relations = SARelation::whereIn('SA4_CODE_2016', $SA4)->orWhereIn('SA3_CODE_2016', $SA3)->pluck('SA2_MAINCODE_2016')->toArray();;

        $filters = Filter::where('table_header', 'SA2')->whereIn('surrogate_key', $relations)->get();
        return $filters;

    }


}
