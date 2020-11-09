<?php

namespace App\Http\Controllers;
use App\Models\DataView;
use App\Models\FilterType;
use App\Models\Centre;
use App\Models\AgeGroup;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function getBarLineChart(Request $request)
    {
        ini_set('memory_limit', -1);
        try {
            $filters = $request->all();
            if(count($filters) > 0 ){
                return self::constructBarLineChar($filters);
            }
            return [];
        }catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function getPieChart(Request $request)
    {
        ini_set('memory_limit', -1);
        try {
            $filters = $request->all();
            if(count($filters) > 0 ){
                return self::constructPieChar($filters);
            }
            return [];

        }catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    // Compare against parents
    private function getParentsCount($filters){
        return count(array_unique(array_column($filters, 'parent_id')));
    }

    private function constructBarLineChar($filters){
        $result = [];
        $data_view = new DataView;
        $filter_type_date_range=FilterType::where('name', '=', 'Date range')->firstOrFail();
        $years = self::getFilterValuesIn($filters, $filter_type_date_range);
        $filter_type_service_provider=FilterType::where('name', '=', 'Service provider')->firstOrFail();
        $centres = self::getFilterSurrogateKeyIn($filters, $filter_type_service_provider);
        $data_view = $data_view->selectRaw("centre as Centre, StartDateYear, SUM(FamilyLaw) as FamilyLaw , SUM(CivilLaw) as CivilLaw, SUM(CriminalLaw)  as CriminalLaw")
                                ->whereRaw("YEAR(StartDate) = YEAR(EndDate)")
                                //->whereIn("YEAR(StartDate)", $years )
                                ->whereIn("StartDateYear", $years )
                                ->whereIn("centre", $centres )
                                //->whereRaw("Centre IN (:centres)",['centres' => $centres])
                                ->groupBy("centre" , "StartDateYear" )
                                ->orderBy("StartDateYear")
                                ->get();

        $labels = $years;
        $datasets = self::createBarLineCharDataSet($data_view);
        $result["labels"] = $labels;
        $result["datasets"] = $datasets;


        return $result;
/*
        SELECT dv.centre as Centre, YEAR(dv.StartDate) as 'Year', SUM(dv.FamilyLaw) as FamilyLaw , SUM(dv.CivilLaw) as CivilLaw, SUM(dv.CriminalLaw)  as CriminalLaw
        FROM ddt.DataView dv
        WHERE YEAR(dv.StartDate) = YEAR(dv.EndDate) AND YEAR(dv.StartDate) IN (2017,2018 ,2015) AND dv.Centre IN(1,16) GROUP BY dv.CENTRE, YEAR(dv.StartDate) ORDER BY YEAR(dv.StartDate)
*/
    }

    private function constructPieChar($filters){
        $result = [];
        $data_view = new DataView;
        $filter_type_date_range=FilterType::where('name', '=', 'Date range')->firstOrFail();
        $years = self::getFilterValuesIn($filters, $filter_type_date_range);
        $filter_type_service_provider=FilterType::where('name', '=', 'Service provider')->firstOrFail();
        $centres = self::getFilterSurrogateKeyIn($filters, $filter_type_service_provider);
        $filter_type_demographic = $filter_type_demographics=FilterType::where('name', '=', 'Client demographics')->firstOrFail();
        $ages = self::getFilterSurrogateKeyIn($filters, $filter_type_demographic, "DimAgeGroup");
        $data_view = $data_view->selectRaw("Age, SUM(FamilyLaw) as FamilyLaw , SUM(CivilLaw) as CivilLaw, SUM(CriminalLaw)  as CriminalLaw")
                                ->whereRaw("YEAR(StartDate) = YEAR(EndDate)");
        if(!empty($years)){
            $data_view = $data_view->whereIn("StartDateYear", $years );
        }
        if(!empty($centres)){
            $data_view = $data_view->whereIn("centre", $centres );
        }
        if(!empty($ages)){
            $data_view = $data_view->whereIn("Age", $ages);
        }

        $data_view = $data_view ->groupBy("Age")
                                ->orderBy("Age")
                                ->get();


        //$result["labels"] = $labels;
        //$result["datasets"] = $datasets;


        return self::createPieCharDataSet($data_view);
/*
        SELECT dv.centre as Centre, YEAR(dv.StartDate) as 'Year', SUM(dv.FamilyLaw) as FamilyLaw , SUM(dv.CivilLaw) as CivilLaw, SUM(dv.CriminalLaw)  as CriminalLaw
        FROM ddt.DataView dv
        WHERE YEAR(dv.StartDate) = YEAR(dv.EndDate) AND YEAR(dv.StartDate) IN (2017,2018 ,2015) AND dv.Centre IN(1,16) GROUP BY dv.CENTRE, YEAR(dv.StartDate) ORDER BY YEAR(dv.StartDate)
*/
    }

    private function getFilterValuesIn($filters, $type, $table_name="")
    {
        $values = [];
        foreach ($filters as $key => $filter) {
            if (empty($table_name)) {
                if($filter["filter_type"] == $type->id){
                    $values[]= intval($filter["value"]);
                }
            } else {
                if($filter["filter_type"] == $type->id && $filter["table"] == $tableName){
                    $values[]= intval($filter["value"]);
                }
            }
        }

        return $values;
    }
    private function getFilterSurrogateKeyIn($filters, $type, $tableName="")
    {
        $values = [];
        foreach ($filters as $key => $filter) {
            if(empty($tableName)){
                if($filter["filter_type"] == $type->id){
                    $values[]=intval($filter["surrogate_key"]);
                }
            } else {
                if($filter["filter_type"] == $type->id && $filter["table"] == $tableName){
                    $values[]=intval($filter["surrogate_key"]);
                }
            }
        }
        return $values;
    }
    private function createBarLineCharDataSet($dataView)
    {
        $result = [];
        $temps = [];
        foreach ($dataView as $key => $row) {
            if(!isset($temps[$row["Centre"]]["label"])){
                $temps[$row["Centre"]]["label"] = Centre::find($row["Centre"])->Centre;
            }
            $temps[$row["Centre"]]["data"][] =  $row["FamilyLaw"] + $row["CivilLaw"]+ $row["CriminalLaw"];
        }
        foreach ($temps as $key => $temp) {
            $result[]=$temp;
        }
        return $result;
    }
    private function createPieCharDataSet($dataView)
    {
        $result = [];
        $temps = [];
        $labels=[];
        $temps["label"] = "Age groups";
        foreach ($dataView as $key => $row) {
            $labels[]=AgeGroup::find($row["Age"])->AgeGroup;
            $temps["data"][] =  $row["FamilyLaw"] + $row["CivilLaw"]+ $row["CriminalLaw"];
        }
        $result["labels"] = $labels;
        $result["datasets"][] = $temps;

        return $result;
    }
}
