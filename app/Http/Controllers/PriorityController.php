<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function getPriorityMapData(Request $request)
    {
        try {
            $indicator = $request->input('property');
            $json_geo = file_get_contents(storage_path() .'\geojson\priority_lga.geojson');
            $json_data_geo = json_decode($json_geo,true);
            $json_data_geo['total'] = self::getIndicatorTotal($indicator, $json_data_geo['features']);
            return $json_data_geo;

            return self::constructTableData($filters, $groupBy);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function getIndicatorTotal($indicator, $features)
    {
        $total= 0;
        foreach ($features as $key => $feature) {
            $total += $feature['properties'][$indicator];
        }
        return $total;
    }
    //
}
