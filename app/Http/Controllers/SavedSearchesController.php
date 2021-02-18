<?php

namespace App\Http\Controllers;

use App\Models\SavedSearch;
use App\Models\FilterType;
use App\Models\Filter;
use Illuminate\Http\Request;
use DB;

class SavedSearchesController extends Controller
{
    public function get()
    {
        $saved_searches = SavedSearch::where('user_id',auth()->user()->id)->with('filters')->get();
        return $saved_searches;
    }

/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $filters = $request->all();
            if(empty($filters)){
                return "No filters have been selected";
            }
            $filter_ids = array_column($filters, 'id');
            $saved_search = ["user_id"=> auth()->user()->id,
                            ];
            $saved_search = SavedSearch::create($saved_search);
            $saved_search->filters()->sync($filter_ids,false);

            return "Search saved successfully";
        }catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    public function remove($id)
    {
        try{

            SavedSearch::find($id)->filters()->detach();
            $row_deleted = DB::table("saved_searches")->where('id',$id)->delete();
            $saved_searches = SavedSearch::where('user_id',auth()->user()->id)->with('filters')->get();
            return $saved_searches;

        }catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }
}
