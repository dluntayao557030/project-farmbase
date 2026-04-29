<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'category_name' => 'Seeds & Planting Materials',
                'description'   => 'Seeds, seedlings, and planting stock for crops',
            ],
            [
                'category_name' => 'Feed & Nutrition',
                'description'   => 'Animal and fish feeds, supplements, and nutrition products',
            ],
            [
                'category_name' => 'Veterinary & Medicine',
                'description'   => 'Medicines, vaccines, and animal health products',
            ],
            [
                'category_name' => 'Fertilizers & Soil Treatment',
                'description'   => 'Fertilizers and soil enhancers for crops',
            ],
            [
                'category_name' => 'Pesticides & Crop Protection',
                'description'   => 'Pesticides, herbicides, and crop protection chemicals',
            ],
            [
                'category_name' => 'Equipment & Tools',
                'description'   => 'Farm tools, machinery, and operational equipment',
            ],
            [
                'category_name' => 'Water Management',
                'description'   => 'Irrigation, filtration, and water quality supplies',
            ],
            [
                'category_name' => 'Cleaning & Sanitation',
                'description'   => 'Cleaning agents and sanitation materials',
            ],
            [
                'category_name' => 'Packaging & Logistics',
                'description'   => 'Packaging materials and transport supplies',
            ],
            [
                'category_name' => 'Infrastructure & Maintenance',
                'description'   => 'Materials for farm structures and repairs',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['category_name' => $category['category_name']], // Prevent duplicates
                [
                    'description' => $category['description'],
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ]
            );
        }

        $this->command->info('✅ Categories seeded successfully! (' . count($categories) . ' categories)');
    }
}