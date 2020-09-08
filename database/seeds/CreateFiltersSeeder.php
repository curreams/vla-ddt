<?php

use Illuminate\Database\Seeder;
use App\Models\FilterType;
use App\Models\Filter;
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
        //Create Filter Types
        $filterTypes = [
            [
                'name'=> 'Location',
                'description' => 'Location filter',
                'color' => '#0220A8',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Service provider',
                'description' => 'Service provider filter',
                'color' => '#43BCE0',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Area of law',
                'description' => 'Area of law filter',
                'color' => '#245904',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Client demographics',
                'description' => 'Client demographics filter',
                'color' => '#6EB8B4',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Date range',
                'description' => 'Date range filter',
                'color' => '#B858C7',
                'created_by' => 'Admin'
            ]
        ];
        foreach ($filterTypes as $filterType) {
            FilterType::create($filterType);
        }
        // Create Filter based on Class Dimensions
        self::createFilters();

    }

    public function createFilters()
    {
        $filter_type_demographics=FilterType::where('name', '=', 'Client demographics')->firstOrFail();
        $filter_type_service_provider=FilterType::where('name', '=', 'Service provider')->firstOrFail();
        $filter_type_location=FilterType::where('name', '=', 'Location')->firstOrFail();
        self::createAgeGroup($filter_type_demographics->id);
        self::createCentre($filter_type_service_provider->id);
        self::createDisabilityMental($filter_type_demographics->id);
        self::createFinancialDisadvantage($filter_type_demographics->id);
        self::createGender($filter_type_demographics->id);
        self::createHomeless($filter_type_demographics->id);
        self::createIndigeous($filter_type_demographics->id);
        self::createLGA($filter_type_location->id);
        self::createLOTE($filter_type_demographics->id);
        self::createSA2($filter_type_location->id);

    }

    public function createAgeGroup($filter_type)
    {
        $age_groups= AgeGroup::all();
        foreach ($age_groups as $key => $age_group) {
            $filter= [];
            $filter = [
                'name' => $age_group->SurrogateKey."-".$age_group->AgeGroup,
                'table' => "DimAgeGroup",
                'surrogate_key' => $age_group->SurrogateKey,
                'value' => $age_group->AgeGroup,
                'description' => 'Age group '. $age_group->AgeGroup,
                'type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createCentre($filter_type)
    {
        $centres = Centre::all();
        foreach ($centres as $key => $centre) {
            $filter= [];
            $filter = [
                'name' => $centre->SurrogateKey."-".$centre->Centre,
                'table' => "DimCentre",
                'surrogate_key' => $centre->SurrogateKey,
                'value' => $centre->Centre,
                'description' => 'Centre '. $centre->Centre,
                'type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createDisabilityMental($filter_type)
    {
        $disabilityMental = DisabilityMental::all();
        foreach ($disabilityMental as $key => $disability) {
            $filter= [];
            $filter = [
                'name' => $disability->SurrogateKey."-".$disability->DisabilityMental,
                'table' => "DimDisabilityMental",
                'surrogate_key' => $disability->SurrogateKey,
                'value' => $disability->DisabilityMental,
                'description' => 'Mental Disability '. $disability->DisabilityMental,
                'type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createFinancialDisadvantage($filter_type)
    {
        $financialDisadvantages = FinancialDisadvantage::all();
        foreach ($financialDisadvantages as $key => $financialDisadvantage) {
            $filter= [];
            $filter = [
                'name' => $financialDisadvantage->SurrogateKey."-".$financialDisadvantage->FinancialDisadvantage,
                'table' => "DimFinancialDisadvantage",
                'surrogate_key' => $financialDisadvantage->SurrogateKey,
                'value' => $financialDisadvantage->FinancialDisadvantage,
                'description' => 'Financial Disadvantage '. $financialDisadvantage->FinancialDisadvantage,
                'type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createGender($filter_type)
    {
        $genders = Gender::all();
        foreach ($genders as $key => $gender) {
            $filter= [];
            $filter = [
                'name' => $gender->SurrogateKey."-".$gender->Gender,
                'table' => "DimGender",
                'surrogate_key' => $gender->SurrogateKey,
                'value' => $gender->Gender,
                'description' => 'Gender '. $gender->Gender,
                'type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }

    public function createHomeless($filter_type)
    {
        $homeless = Homeless::all();
        foreach ($homeless as $key => $home) {
            $filter= [];
            $filter = [
                'name' => $home->SurrogateKey."-".$home->Homeless,
                'table' => "DimHomeless",
                'surrogate_key' => $home->SurrogateKey,
                'value' => $home->Gender,
                'description' => 'Homeless '. $home->Homeless,
                'type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }
    public function createIndigeous($filter_type)
    {
        $indigenousStatus = IndigenousStatus::all();
        foreach ($indigenousStatus as $key => $indigenous) {
            $filter= [];
            $filter = [
                'name' => $indigenous->SurrogateKey."-".$indigenous->Indigenous,
                'table' => "DimIndigenous",
                'surrogate_key' => $indigenous->SurrogateKey,
                'value' => $indigenous->Gender,
                'description' => 'Indigenous '. $indigenous->Indigenous,
                'type' => $filter_type,
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
                'value' => $value->Gender,
                'description' => 'LGA '. $value->LGA,
                'type' => $filter_type,
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
                'value' => $value->Gender,
                'description' => 'LOTE '. $value->LOTE,
                'type' => $filter_type,
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
                'value' => $value->Gender,
                'description' => 'SA2 '. $value->SA2,
                'type' => $filter_type,
                'created_by' => 'seeder',
                'updated_by' => 'seeder'
            ];
            Filter::create($filter);
        }
    }

}
