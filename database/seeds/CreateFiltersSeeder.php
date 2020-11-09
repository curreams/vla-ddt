<?php

use Illuminate\Database\Seeder;
use App\Models\FilterType;
use App\Models\Filter;
use App\Models\ShowType;
/*CLASS Models*/
use App\Models\AgeGroup;
use App\Models\Centre;
use App\Models\Gender;
use App\Models\IndigenousStatus;
use App\Models\DisabilityMental;
use App\Models\FinancialDisadvantage;
use App\Models\Homeless;
use App\Models\LGA;
use App\Models\LOTE;
use App\Models\SA2;
use App\Models\ClassDimension;
/******** */

class CreateFiltersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create  Show Types
        $show_type = [
            'name' => 'Cramped'
        ];

        $show_type_cr = ShowType::create($show_type);
        $show_type = [];
        $show_type = [
            'name' => 'Expandable'
        ];
        $show_type_ex = ShowType::create($show_type);

        //Create Filter Types
        $filter_types = [
            [
                'name'=> 'Location',
                'description' => 'Location filter',
                'searchable'=> true,
                'show_type' => $show_type_ex->id,
                'color' => '#0220A8',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Service provider',
                'description' => 'Service provider filter',
                'searchable'=> true,
                'show_type' => $show_type_ex->id,
                'color' => '#43BCE0',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Area of law',
                'description' => 'Area of law filter',
                'searchable'=> false,
                'show_type' => $show_type_cr->id,
                'color' => '#245904',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Client demographics',
                'description' => 'Client demographics filter',
                'searchable'=> false,
                'show_type' => $show_type_ex->id,
                'color' => '#6EB8B4',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Date range',
                'description' => 'Date range filter',
                'searchable'=> false,
                'show_type' => $show_type_cr->id,
                'color' => '#B858C7',
                'created_by' => 'Admin'
            ]
        ];
        foreach ($filter_types as $filter_type) {
            FilterType::create($filter_type);
        }
        // Create Filter based on Class Dimensions
        self::createFilters();

    }

    public function createFilters()
    {
        $filter_type_demographics=FilterType::where('name', '=', 'Client demographics')->firstOrFail();
        $filter_type_service_provider=FilterType::where('name', '=', 'Service provider')->firstOrFail();
        $filter_type_area_of_law=FilterType::where('name', '=', 'Area of law')->firstOrFail();
        $filter_type_location=FilterType::where('name', '=', 'Location')->firstOrFail();
        $filter_type_date_range=FilterType::where('name', '=', 'Date range')->firstOrFail();
        $filter= [];
        $filter = [
            'name' => "Comunity legal centres",
            'table' => "",
            'surrogate_key' => 0,
            'table_header' => "Centre",
            'value' => "Comunity legal centres",
            'description' => 'Comunity legal centres parent',
            'filter_type' => $filter_type_service_provider->id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        $filter_service_provider_clc = Filter::create($filter);
        $filter = [
            'name' => "Victoria Legal Aid",
            'table' => "",
            'surrogate_key' => 0,
            'table_header' => "Centre",
            'value' => "Victoria Legal Aid",
            'description' => 'Victoria Legal Aid parent',
            'filter_type' => $filter_type_service_provider->id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        $filter_service_provider_vla = Filter::create($filter);
        self::createCentre($filter_type_service_provider->id, $filter_service_provider_clc, $filter_service_provider_vla);
        $filter= [];
        $filter = [
            'name' => "Area of law",
            'table' => "",
            'surrogate_key' => 0,
            'value' => "Area of law",
            'description' => 'Area of law parent',
            'filter_type' => $filter_type_area_of_law->id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        $filter_area_of_law = Filter::create($filter);
        self::createAreaOfLaw($filter_type_area_of_law->id, $filter_area_of_law);
        $filter= [];
        $filter = [
            'name' => "Age",
            'table' => "",
            'table_header' => "Age",
            'surrogate_key' => 0,
            'value' => "Age",
            'description' => 'Age parent',
            'filter_type' => $filter_type_demographics->id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        $filter_age = Filter::create($filter);
        self::createAgeGroup($filter_type_demographics->id, $filter_age);
        $filter= [];
        $filter = [
            'name' => "Disability and/or mental health",
            'table' => "",
            'table_header' => "DisabilityMental",
            'surrogate_key' => 0,
            'value' => "Disability and/or mental health",
            'description' => 'Disability and/or mental health parent',
            'filter_type' => $filter_type_demographics->id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        $filter_disability = Filter::create($filter);
        self::createDisabilityMental($filter_type_demographics->id, $filter_disability);
        $filter= [];
        $filter = [
            'name' => "Financial disadvantage",
            'table' => "",
            'surrogate_key' => 0,
            'table_header' => "FinancialDisadvantage",
            'value' => "Financial disadvantage",
            'description' => 'Financial disadvantage parent',
            'filter_type' => $filter_type_demographics->id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        $filter_financial = Filter::create($filter);
        self::createFinancialDisadvantage($filter_type_demographics->id,$filter_financial);
        $filter= [];
        $filter = [
            'name' => "Gender",
            'table' => "",
            'table_header' => "Gender",
            'surrogate_key' => 0,
            'value' => "Gender",
            'description' => 'Gender parent',
            'filter_type' => $filter_type_demographics->id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        $filter_gender = Filter::create($filter);
        self::createGender($filter_type_demographics->id, $filter_gender);
        $filter= [];
        $filter = [
            'name' => "Homeless",
            'table' => "",
            'table_header' => "Homeless",
            'surrogate_key' => 0,
            'value' => "Homeless",
            'description' => 'Homeless parent',
            'filter_type' => $filter_type_demographics->id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        $filter_homeless = Filter::create($filter);
        self::createHomeless($filter_type_demographics->id, $filter_homeless);
        $filter= [];
        $filter = [
            'name' => "Aboriginal and/or Torres Strait Islander",
            'table' => "",
            'table_header' => "Indigenous",
            'surrogate_key' => 0,
            'value' => "Aboriginal and/or Torres Strait Islander",
            'description' => 'Aboriginal and/or Torres Strait Islander parent',
            'filter_type' => $filter_type_demographics->id,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        $filter_indigenous= Filter::create($filter);
        self::createIndigenous($filter_type_demographics->id,$filter_indigenous);
        //Create date filters
        self::createDate($filter_type_date_range->id);
        /*self::createLGA($filter_type_location->id);
        self::createLOTE($filter_type_demographics->id);
        self::createSA2($filter_type_location->id);*/

    }

    public function createAgeGroup($filter_type,$filter_age)
    {
        $Ages= AgeGroup::all();
        foreach ($Ages as $key => $Age) {
            $filter= [];
            $filter = [
                'name' => $Age->SurrogateKey."-".$Age->AgeGroup,
                'table' => "DimAgeGroup",
                'table_header' => "Age",
                'parent_id' => $filter_age->id,
                'surrogate_key' => $Age->SurrogateKey,
                'value' => $Age->AgeGroup,
                'description' => 'Age group '. $Age->AgeGroup,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createCentre($filter_type, $filter_service_provider_clc, $filter_service_provider_vla)
    {
        $centres = Centre::all();
        foreach ($centres as $key => $centre) {
            $filter= [];
            $filter = [
                'name' => $centre->SurrogateKey."-".$centre->Centre,
                'table' => "DimCentre",
                'table_header' => "Centre",
                'parent_id'=> $centre->Type == "CLC" ?  $filter_service_provider_clc->id : $filter_service_provider_vla->id,
                'surrogate_key' => $centre->SurrogateKey,
                'value' => $centre->Centre,
                'description' => 'Centre '. $centre->Centre,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createDisabilityMental($filter_type,$filter_disability)
    {
        $disabilityMental = DisabilityMental::all();
        foreach ($disabilityMental as $key => $disability) {
            $filter= [];
            $filter = [
                'name' => $disability->SurrogateKey."-".$disability->DisabilityMental,
                'table' => "DimDisabilityMental",
                'table_header' => "DisabilityMental",
                'parent_id'=>$filter_disability->id,
                'surrogate_key' => $disability->SurrogateKey,
                'value' => $disability->DisabilityMental,
                'description' => 'Mental Disability '. $disability->DisabilityMental,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createFinancialDisadvantage($filter_type,$filter_financial)
    {
        $financialDisadvantages = FinancialDisadvantage::all();
        foreach ($financialDisadvantages as $key => $financialDisadvantage) {
            $filter= [];
            $filter = [
                'name' => $financialDisadvantage->SurrogateKey."-".$financialDisadvantage->FinancialDisadvantage,
                'table' => "DimFinancialDisadvantage",
                'table_header' => "FinancialDisadvantage",
                'parent_id' => $filter_financial->id,
                'surrogate_key' => $financialDisadvantage->SurrogateKey,
                'value' => $financialDisadvantage->FinancialDisadvantage,
                'description' => 'Financial Disadvantage '. $financialDisadvantage->FinancialDisadvantage,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createGender($filter_type,$filter_gender)
    {
        $genders = Gender::all();
        foreach ($genders as $key => $gender) {
            $filter= [];
            $filter = [
                'name' => $gender->SurrogateKey."-".$gender->Gender,
                'table' => "DimGender",
                'table_header' => "Gender",
                'parent_id' => $filter_gender->id,
                'surrogate_key' => $gender->SurrogateKey,
                'value' => $gender->Gender,
                'description' => 'Gender '. $gender->Gender,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }

    public function createHomeless($filter_type,$filter_homeless)
    {
        $homeless = Homeless::all();
        foreach ($homeless as $key => $home) {
            $filter= [];
            $filter = [
                'name' => $home->SurrogateKey."-".$home->Homeless,
                'table' => "DimHomeless",
                'table_header' => "Homeless",
                'parent_id'=>$filter_homeless->id,
                'surrogate_key' => $home->SurrogateKey,
                'value' => $home->Homeless,
                'description' => 'Homeless '. $home->Homeless,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createIndigenous($filter_type,$filter_indigenous)
    {
        $indigenousStatus = IndigenousStatus::all();
        foreach ($indigenousStatus as $key => $indigenous) {
            $filter= [];
            $filter = [
                'name' => $indigenous->SurrogateKey."-".$indigenous->Indigenous,
                'table' => "DimIndigenous",
                'table_header' => "Indigenous",
                'parent_id' => $filter_indigenous->id,
                'surrogate_key' => $indigenous->SurrogateKey,
                'value' => $indigenous->Indigenous,
                'description' => 'Indigenous '. $indigenous->Indigenous,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }

    public function createLGA($filter_type)
    {
        $LGA = LGA::all();
        foreach ($LGA as $key => $value) {
            $filter= [];
            $filter = [
                'name' => $value->SurrogateKey."-".$value->LGA,
                'table' => "DimLGA",
                'surrogate_key' => $value->SurrogateKey,
                'value' => $value->LGA,
                'description' => 'LGA '. $value->LGA,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createLOTE($filter_type)
    {
        $LOTE = LOTE::all();
        foreach ($LOTE as $key => $value) {
            $filter= [];
            $filter = [
                'name' => $value->SurrogateKey."-".$value->LOTE,
                'table' => "DimLOTE",
                'surrogate_key' => $value->SurrogateKey,
                'value' => $value->LOTE,
                'description' => 'LOTE '. $value->LOTE,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createSA2($filter_type)
    {
        $SA2 = SA2::all();
        foreach ($SA2 as $key => $value) {
            $filter= [];
            $filter = [
                'name' => $value->SurrogateKey."-".$value->SA2,
                'table' => "DimSA2",
                'surrogate_key' => $value->SurrogateKey,
                'value' => $value->SA2,
                'description' => 'SA2 '. $value->SA2,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }

    public function createAreaOfLaw($filter_type, $filter_area_of_law)
    {
        $filter= [];
        $filter = [
            'name' => "1-FamilyLaw",
            'table' => "",
            'surrogate_key' => 1,
            'parent_id' => $filter_area_of_law->id,
            'value' => "Family Law",
            'description' => 'Area of law Family Law',
            'filter_type' => $filter_type,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        Filter::create($filter);
        $filter= [];
        $filter = [
            'name' => "2-CivilLaw",
            'table' => "",
            'surrogate_key' => 2,
            'parent_id' => $filter_area_of_law->id,
            'value' => "Civil Law",
            'description' => 'Area of law Civil Law',
            'filter_type' => $filter_type,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        Filter::create($filter);
        $filter= [];
        $filter = [
            'name' => "1-CriminalLaw",
            'table' => "",
            'surrogate_key' => 3,
            'parent_id' => $filter_area_of_law->id,
            'value' => "Criminal Law",
            'description' => 'Area of law Criminal Law',
            'filter_type' => $filter_type,
            'created_by' => 'seeder',
            'updated_by' => 'seeder'
        ];
        Filter::create($filter);
    }

    public function createDate($filter_type)
    {
        $sql = "SELECT DISTINCT YEAR(StartDate) as year_value FROM ddt.DataView ORDER BY year_value ASC";
        $years = DB::connection('sqlsrv')->select($sql);
        foreach ($years as $key => $year) {
            $filter= [];
            $filter = [
                'name' => $key . "-" . $year->year_value,
                'table' => "",
                'table_header' => "",
                'surrogate_key' => $key,
                'value' => $year->year_value,
                'description' => 'Year '. $year->year_value,
                'filter_type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }

}
