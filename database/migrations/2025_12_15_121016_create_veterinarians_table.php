<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('veterinarians', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_name');
            $table->string('veterinarian_name');
            $table->string('license_number');
            $table->string('contact_number');
            $table->string('email');
            $table->text('clinic_address');
            $table->string('clinic_hours');
            $table->string('specialization');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veterinarians');
    }
};
