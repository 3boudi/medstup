<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSpecializationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->string('specialization')->unique();
            $table->timestamps(); // Optional but recommended
        });

        // Insert all medical specializations
        $specializations = [
            'Allergy and Immunology',
            'Anesthesiology',
            'Cardiology',
            'Cardiothoracic Surgery',
            'Dermatology',
            'Emergency Medicine',
            'Endocrinology',
            'Family Medicine',
            'Gastroenterology',
            'General Surgery',
            'Geriatrics',
            'Hematology',
            'Infectious Disease',
            'Internal Medicine',
            'Medical Genetics',
            'Nephrology',
            'Neurology',
            'Neurosurgery',
            'Nuclear Medicine',
            'Obstetrics and Gynecology',
            'Oncology',
            'Ophthalmology',
            'Orthopedic Surgery',
            'Otolaryngology (ENT)',
            'Pathology',
            'Pediatrics',
            'Physical Medicine and Rehabilitation',
            'Plastic Surgery',
            'Psychiatry',
            'Pulmonology',
            'Radiation Oncology',
            'Radiology',
            'Rheumatology',
            'Sports Medicine',
            'Thoracic Surgery',
            'Urology',
            'Vascular Surgery'
        ];

        foreach ($specializations as $specialty) {
            DB::table('specializations')->insertOrIgnore([
                'specialization' => $specialty,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('specializations');
    }
}