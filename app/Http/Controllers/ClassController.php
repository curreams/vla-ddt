<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
/*CLASS Models*/
use App\Models\AgeGroup;
use App\Models\Centre;
use App\Models\Gender;
use App\Models\IndigenousStatus;
use App\Models\ClassDimension;
/******** */
use App\Models\FilterType;

/**
 * Class to manage the data that comes from CLASS
 */
class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $class_dimensions = self::bringClassDimensions();
        $filter_types = FilterType::all();
        return view('class.index',compact('class_dimensions','filter_types'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Store the selected dimensions as Filters
     *
     * @param Request $request
     * @return void
     */
    public function storeFilters(Request $request)
    {
        $input = $request->all();

        foreach ($input as $key => $dimension) {
            if($dimension['selected']){


            }
            $split_dimension = explode("-", $dimension);
        }
        return "Hello";

    }

    public function  bringClassDimensions()
    {
        $class_dimensions = [];
        $age_groups= AgeGroup::orderBy('SurrogateKey','ASC')->get();
        foreach ($age_groups as $key => $age_group) {
            $class_dimension = [
                "surrogate_key" => $age_group->SurrogateKey,
                "value"         => $age_group->AgeGroup,
                "table"         => "Age group",
                "id"            => $age_group->SurrogateKey . "-agegroup",
                "selected"      => false,
                "filter_type"   => "",
            ];
            $class_dimensions[] = $class_dimension;
        }
        //TODO add other dimensions to the list
        $centres = Centre::all();
        $genders = Gender::all();
        $indigenous_status = IndigenousStatus::all();

        return $class_dimensions;
    }


    public function getDimensions()
    {

        $filter_type=FilterType::where('name', '=', 'Client demographics')->firstOrFail();
        return $filter_type->id;
        /*$age_groups= AgeGroup::all();
        $centres = Centre::all();
        $genders = Gender::all();
        $indigenous_status = IndigenousStatus::all();*/

    }




}
