<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            
            // --- FIX THIS LINE ---
            // Change 'owner_id' to 'user_id' so it matches your Controller
            $table->unsignedBigInteger('user_id'); 
            // ---------------------

            $table->string('owner_name')->nullable(); // We keep this for backup
            $table->string('name');
            $table->string('species');
            $table->string('sex');
            $table->string('breed')->nullable();
            $table->date('birthdate')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('color')->nullable();
            $table->string('vaccination_status');
            $table->string('photo_path')->nullable();
            $table->date('registration_date');
            $table->timestamps();

            // Optional: Link it formally to users table (Recommended)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
