<?php

namespace App\Http\Controllers;
use App\Models\DataView;
use App\Models\FilterType;
use App\Models\Filter;
use App\Models\Centre;
use App\Models\AgeGroup;
use App\Models\Gender;
use App\Models\FinancialDisadvantage;
use App\Models\DisabilityMental;
use App\Models\IndigenousStatus;
use App\Models\Homeless;
use App\Models\LOTE;
use App\Models\SA2;

use RecursiveIteratorIterator;
use RecursiveArrayIterator;

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
            $params = $request->input();
            $filters = $params[0];
            $snapshot = $params[1];
            if(count($filters) > 0 ){
                return self::constructPieChar($filters, $snapshot);
            }
            return [];

        }catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function getMapValues(Request $request)
    {
        ini_set('memory_limit', -1);
        try {
            $params = $request->all();
            $filters = $params[0];
            $groupBy = $params[1];

            return self::getMapProperties($filters, $groupBy);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getTable(Request $request)
    {
        ini_set('memory_limit', -1);
        try {
            $params = $request->all();
            $filters = $params[0];
            $groupBy = $params[1];

            return self::constructTableData($filters, $groupBy);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    private function constructTableData($filters, $groupBy)
    {
        $result = [];
        $filter_type_aol = FilterType::where('name', '=', 'Area of law')->firstOrFail();
        $filter_type_sp = FilterType::where('name', '=', 'Service provider')->firstOrFail();
        $filter_type_loc = FilterType::where('name', '=', 'Location')->firstOrFail();
        $where_values = self::getWhereValue($filters, $filter_type_aol);
        $select_values = self::getSelectValueTable($filters,$groupBy,$filter_type_aol,$filter_type_sp, $filter_type_loc);
        if(!empty($select_values)){
            $data_view = new DataView;
            $data_view = $data_view->selectRaw($select_values);
            foreach ($where_values as $key => $where_value) {
                $data_view = $data_view->whereIn($key, $where_value);
            }
            $data_view = $data_view ->groupBy($groupBy, 'Centre', 'StartDateYear' )
                                    ->orderBy($groupBy)
                                    ->orderBy('Centre')
                                    ->orderBy('StartDateYear')
                                    ->get()
                                    ->toArray();
            $result = self::createTableData($data_view,$groupBy);
        }
        return $result;
    }



    public function getMapProperties($filters, $groupBy){
        $result = [];
        $filter_type_aol = FilterType::where('name', '=', 'Area of law')->firstOrFail();
        $filter_type_sp = FilterType::where('name', '=', 'Service provider')->firstOrFail();
        $where_values = self::getWhereValue($filters, $filter_type_aol);
        $select_values = self::getSelectValueMap($filters,$filter_type_aol,$groupBy);
        $select_values_total = self::getSelectValueMap($filters,$filter_type_aol,"", true);
        $select_providers = self::getSelectValueProviders($filters,$filter_type_aol,$filter_type_sp);
        $data_view = new DataView;
        $data_view_total = new DataView;
        $data_view_providers = new DataView;

        $data_view = $data_view->selectRaw($select_values);
        $data_view_total = $data_view_total->selectRaw($select_values_total);
        !empty($select_providers)? $data_view_providers = $data_view_providers->selectRaw($select_providers):null;



        foreach ($where_values as $key => $where_value) {
            $data_view = $data_view->whereIn($key, $where_value );
            $data_view_total = $data_view_total->whereIn($key, $where_value );
            !empty($select_providers)? $data_view_providers = $data_view_providers->whereIn($key, $where_value ):null;
        }

        $data_view = $data_view ->groupBy($groupBy )
                                ->orderBy($groupBy)
                                ->get()
                                ->toArray();

        $data_view_total = $data_view_total->get()->toArray();
        $data_view_total["Total"] = array_shift($data_view_total)["Total"];


        $data_view_providers = !empty($select_providers) ?
                                                    $data_view_providers->groupBy("Centre")
                                                        ->orderBy("Centre")
                                                        ->get()
                                                        ->toArray() :
                                [];
        $result = array_merge(self::createMapData($data_view,$groupBy), $data_view_total, self::createProviderResponse($data_view_providers));

        return $result;
    }

    private function createMapData($data_view, $groupBy)
    {
        $json = file_get_contents(storage_path() .'\geojson\sa2_2016.geojson');
        //Decode JSON
        $json_data = json_decode($json,true);
        $features = $json_data['features'];
        $data_view = self::modifyDataView($data_view);
        $length = count($features);
        for ($i=0; $i < $length ; $i++) {
            if(isset($data_view[$features[$i]['properties']['sa2']])){
                $features[$i]['properties']['client_count'] = $data_view[$features[$i]['properties']['sa2']];
            }
        }
        $json_data['features'] = $features;
        return $json_data;
    }

    private function modifyDataView(array $array) {
        $return = [];
        array_walk($array, function($item, $key) use (&$return) { $return[$item["SA2"]] = $item["Total"]; });
        return $return;
    }



    private function constructBarLineChar($filters){
        $result = [];
        $years=[];
        $filter_type_aol = FilterType::where('name', '=', 'Area of law')->firstOrFail();
        $where_values = self::getWhereValue($filters, $filter_type_aol);
        $where_values = self::evaluateYearWhere($where_values);
        $select_values = self::getSelectValueLineBarChart($filters,$filter_type_aol);
        $years = $where_values['StartDateYear'];
        $data_view = new DataView;
        $data_view = $data_view->selectRaw($select_values);

        foreach ($where_values as $key => $where_value) {
            $data_view = $data_view->whereIn($key, $where_value );
        }

        $data_view = $data_view ->groupBy("Centre" , "StartDateYear" )
                                ->orderBy("Centre")
                                ->orderBy("StartDateYear")
                                ->get()
                                ->toArray();

        $datasets = self::createBarLineCharDataSet($data_view,$years);
        $result["labels"] = $years;
        $result["datasets"] = $datasets;


        return $result;
    }

    private function constructPieChar($filters, $snapshot_by){
        $result = [];
        $filter_type_aol = FilterType::where('name', '=', 'Area of law')->firstOrFail();
        $where_values= self::getWhereValue($filters, $filter_type_aol);
        $select_values = self::getSelectValuePieChart($filters, $snapshot_by, $filter_type_aol);
        $data_view = new DataView;
        $data_view = $data_view->selectRaw($select_values)
                                ->whereRaw("YEAR(StartDate) = YEAR(EndDate)");
        foreach ($where_values as $key => $where_value) {
            $data_view = $data_view->whereIn($key, $where_value );
        }
        $data_view = $data_view ->groupBy($snapshot_by)
                                ->orderBy($snapshot_by)
                                ->get();

        return self::createPieCharDataSet($data_view, $snapshot_by);

    }


    private function  getWhereValue($filters, $filter_type_aol){
        $where_values = [];
        foreach ($filters as $key => $filter) {
            if($filter["filter_type"] != $filter_type_aol->id) {
                $where_values[$filter["table_header"]][]=strval($filter["surrogate_key"]);
            }

        }
        return $where_values;
    }

    private function evaluateYearWhere($where_values){
        if(!array_key_exists ( "StartDateYear", $where_values )){
            $filters = Filter::where('table_header','=','StartDateYear')->pluck('value')->toArray();
            $where_values["StartDateYear"]= $filters;
        }
        return $where_values;

    }

    private function getSelectValuePieChart($filters , $snapshot_by, $filter_type_aol){
        $select_value = $snapshot_by . ", ";
        $aol_filters = [];
        foreach ($filters as $key => $filter) {
            if($filter["filter_type"] == $filter_type_aol->id) {
                $aol_filters[] = $filter["table_header"];
            }
        }
        if(empty($aol_filters)){
            $select_value .=  "SUM(FamilyLaw + CivilLaw +  CriminalLaw) as Total";
        } else {
            $select_value .= "SUM(" .implode(" + ", $aol_filters). ") as Total";
        }
        return $select_value;
    }

    private function getSelectValueLineBarChart($filters , $filter_type_aol){
        $select_value = "centre as Centre, StartDateYear, ";
        $aol_filters = [];
        foreach ($filters as $key => $filter) {
            if($filter["filter_type"] == $filter_type_aol->id) {
                $aol_filters[] = $filter["table_header"];
            }
        }
        if(empty($aol_filters)){
            $select_value .=  "SUM(FamilyLaw + CivilLaw + CriminalLaw) as Total";
        } else {
            $select_value .= "SUM(" .implode(" + ", $aol_filters). ") as Total";
        }
        return $select_value;
    }

    private function getSelectValueMap($filters , $filter_type_aol, $group_by, $select_total = false){
        $select_value = $select_total? "": $group_by . ", ";
        $aol_filters = [];
        foreach ($filters as $key => $filter) {
            if($filter["filter_type"] == $filter_type_aol->id) {
                $aol_filters[] = $filter["table_header"];
            }
        }
        if(empty($aol_filters)){
            $select_value .=  "SUM(FamilyLaw + CivilLaw +  CriminalLaw) as Total";
        } else {
            $select_value .= "SUM(" .implode(" + ", $aol_filters). ") as Total";
        }
        return $select_value;
    }

    private function getSelectValueProviders($filters , $filter_type_aol, $filter_type_sp){
        $select_value = "";
        $aol_filters = [];
        $sp_filters = false;
        foreach ($filters as $key => $filter) {
            if($filter["filter_type"] == $filter_type_aol->id){
                $aol_filters[] = $filter["table_header"];
            }
            if($filter["filter_type"] == $filter_type_sp->id) {
                $sp_filters = true;
            }
        }
        if($sp_filters){
            $select_value = "Centre, ";
            if(empty($aol_filters)){
                $select_value .=  "SUM(FamilyLaw + CivilLaw +  CriminalLaw) as Total";
            } else {
                $select_value .= "SUM(" .implode(" + ", $aol_filters). ") as Total";
            }
        }
        return $select_value;
    }

    private function getSelectValueTable($filters,$groupBy,$filter_type_aol,$filter_type_sp,$filter_type_loc){
        $select_value = "";
        $aol_filters = [];
        $sp_filters = false;
        foreach ($filters as $key => $filter) {
            if($filter["filter_type"] == $filter_type_aol->id){
                $aol_filters[] = $filter["table_header"];
            }
            if($filter["filter_type"] == $filter_type_sp->id) {
                $sp_filters = true;
            }
            if($filter["filter_type"] == $filter_type_loc->id) {
                $sp_filters = true;
            }
        }
        if($sp_filters){
            $select_value = $groupBy.", Centre, StartDateYear, ";
            if(empty($aol_filters)){
                $select_value .=  "SUM(FamilyLaw + CivilLaw +  CriminalLaw) as Total";
            } else {
                $select_value .= "SUM(" .implode(" + ", $aol_filters). ") as Total";
            }
        }
        return $select_value;
    }

    private function createBarLineCharDataSet($dataView, $years)
    {
        $result = [];
        $temps = [];
        foreach ($dataView as $key => $row) {
            if(!isset($temps[$row["Centre"]]["label"])){
                $temps[$row["Centre"]]["label"] = Centre::find($row["Centre"])->Centre;
                $temps[$row["Centre"]]["data"]= array_fill(0, count($years), 0);
            }
            $year_index =  array_search($row["StartDateYear"], $years);
            $temps[$row["Centre"]]["data"][$year_index] =  intval($row["Total"]);

        }
        foreach ($temps as $key => $temp) {
            $result[]=$temp;
        }
        return $result;
    }
    private function createPieCharDataSet($dataView, $snapshot_by)
    {
        $result = [];
        $temps = [];
        $labels=[];
        $temps["label"] = "Demographic groups";
        foreach ($dataView as $key => $row) {
            switch ($snapshot_by) {
                case 'Age':
                    $labels[]=AgeGroup::find($row[$snapshot_by])->AgeGroup;
                    break;
                case 'Gender':
                    $labels[]=Gender::find($row[$snapshot_by])->Gender;
                    break;
                case 'FinancialDisadvantage':
                    $labels[]=FinancialDisadvantage::find($row[$snapshot_by])->FinancialDisadvantage;
                    break;
                case 'DisabilityMental':
                    $labels[]=DisabilityMental::find($row[$snapshot_by])->DisabilityMental;
                    break;
                case 'Indigenous':
                    $labels[]=IndigenousStatus::find($row[$snapshot_by])->Indigenous;
                    break;
                case 'Homeless':
                    $labels[]=Homeless::find($row[$snapshot_by])->Homeless;
                    break;
                case 'LOTE':
                    $labels[]=LOTE::find($row[$snapshot_by])->LOTE;
                    break;
                default:

                    break;
            }
            $temps["data"][] =  intval($row["Total"]);
        }
        $result["labels"] = $labels;
        if(isset($temps["data"])){
            $result["datasets"][] = $temps;
        }

        return $result;
    }

    private function createProviderResponse($dataView)
    {
        $result = [];
        $temps = [];
        foreach ($dataView as $key => $row) {
            if(!isset($temps[$row["Centre"]])){
                $temps[$row["Centre"]]['Centre'] = Centre::find($row["Centre"])->Centre;
            }
            $temps[$row["Centre"]]["Total"]=  intval($row["Total"]);
        }
        foreach ($temps as $key => $temp) {
            $result['Providers'][]=$temp;
        }
        return $result;
    }
    private function createTableData($data_view, $groupBy){
        $result=[];
        $total = 0;
        $temp_result=[];
        foreach ($data_view as $key => $row) {
            $result['years'][$row['StartDateYear']]=$row['StartDateYear'];
            if(!isset($temp_result['location'][$row[$groupBy]])){
                $temp_result['location'][$row[$groupBy]] = SA2::find($row[$groupBy])->SA2;
            }
            if(!isset($temp_result['centre'][$row['Centre']])){
                $temp_result['centre'][$row['Centre']] = Centre::find($row["Centre"])->Centre;
            }
            $result['data'][$temp_result['location'][$row[$groupBy]]][$temp_result['centre'][$row['Centre']]][$row['StartDateYear']] = $row['Total'];
         
            $total = $total + $row['Total'];
        }
        $result['total'] = $total;
        //TODO: try to improve this triple for each.
        foreach ($result['data'] as $keyl => $location) {
            foreach ($location as $keyc => $centre) {
                foreach ($result['years'] as $keyy => $year) {
                    if( !isset( $result['data'][$keyl][$keyc][$keyy] ) ){
                        $result['data'][$keyl][$keyc][$keyy] = "0";
                    }
                }
            }

        }
        return $result;
    }

}
