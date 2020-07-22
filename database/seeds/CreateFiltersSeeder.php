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
    }
}
