<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NLASController extends Controller
{
    /* Just One execution to create the Geo NLAS file*/
    public function setNLASData()
    {
        $json_geo = file_get_contents(storage_path() .'\geojson\lga.geojson');
        $json_data_geo = json_decode($json_geo,true);
        $features = $json_data_geo['features'];

        $json_nlas = file_get_contents(storage_path() .'\geojson\lga_nlas.json');
        $json_data_nlas = json_decode($json_nlas,true);
        $nlaslga = $json_data_nlas['lga_nlas'];
        $length = count($features);
        for ($i=0; $i < $length ; $i++) {
            $key = array_search($features[$i]['properties']['lga_code'], array_column($nlaslga, 'lga_code'));
            if($key >=0){
                $features[$i]['properties'] =  array_merge($features[$i]['properties'], $nlaslga[$key] );
            }
        }

        $json_data_geo['features'] = $features;

        $fp = fopen(storage_path() .'\geojson\nlas_lga.geojson', 'w');
        fwrite($fp, json_encode($json_data_geo));
        fclose($fp);
    }

    public function getNLASMapData(Request $request)
    {
        try {
            $indicator = $request->input('property');
            $json_geo = file_get_contents(storage_path() .'\geojson\nlas_lga.geojson');
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
}
