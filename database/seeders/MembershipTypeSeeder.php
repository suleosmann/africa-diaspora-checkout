<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MembershipType;

class MembershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Basic',
                'amount' => 1000,
                'description' => 'Entry-level membership suitable for individuals joining the platform.',
                'status' => true,
            ],
            [
                'name' => 'Premium',
                'amount' => 5000,
                'description' => 'Access to premium resources, events, and exclusive member benefits.',
                'status' => true,
            ],
            [
                'name' => 'Gold',
                'amount' => 10000,
                'description' => 'Elite membership with full privileges and recognition.',
                'status' => true,
            ],
        ];

        foreach ($types as $type) {
            MembershipType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
