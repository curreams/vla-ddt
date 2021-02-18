<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FilterType;
use App\Models\Filter;
use App\Models\SavedSearch;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $filter_types = FilterType::orderBy('id','ASC')->with('showType')->with('filters')->with('filters.children')->get();
        $saved_searches = SavedSearch::where('user_id',auth()->user()->id)->with('filters')->get();
        $SA4_filters = Filter::orderBy('value','ASC')->where('table_header','SA4')->get();
        $SA3_filters = Filter::orderBy('value','ASC')->where('table_header','SA3')->get();
        return view('home',compact('filter_types', 'SA4_filters', 'SA3_filters'));

    }
}
