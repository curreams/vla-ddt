<?php

use Illuminate\Database\Seeder;
use App\Models\FilterType;

class CreateFiltersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $filterTypes = [
            [
                'name'=> 'Location',
                'description' => 'Location filter',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Service provider',
                'description' => 'Service provider filter',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Area of law',
                'description' => 'Area of law filter',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Client demographics',
                'description' => 'Client demographics filter',
                'created_by' => 'Admin'
            ],
            [
                'name'=> 'Date range',
                'description' => 'Date range filter',
                'created_by' => 'Admin'
            ]
        ];
        foreach ($filterTypes as $filterType) {
            FilterType::create($filterType);
        }
    }
}
