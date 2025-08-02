<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Specialization;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            [
                'name' => 'Cardiology',
                'description' => 'Heart and cardiovascular system specialists',
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Skin, hair, and nail specialists',
            ],
            [
                'name' => 'Neurology',
                'description' => 'Brain and nervous system specialists',
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Bone, joint, and muscle specialists',
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Children\'s health specialists',
            ],
            [
                'name' => 'Psychiatry',
                'description' => 'Mental health specialists',
            ],
            [
                'name' => 'General Medicine',
                'description' => 'General healthcare and family medicine',
            ],
            [
                'name' => 'Gynecology',
                'description' => 'Women\'s reproductive health specialists',
            ],
            [
                'name' => 'Ophthalmology',
                'description' => 'Eye and vision specialists',
            ],
            [
                'name' => 'ENT',
                'description' => 'Ear, nose, and throat specialists',
            ],
        ];

        foreach ($specializations as $specialization) {
            Specialization::firstOrCreate(
                ['name' => $specialization['name']],
                $specialization
            );
        }
    }
}