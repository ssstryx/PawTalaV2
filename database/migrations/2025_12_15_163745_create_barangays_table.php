<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Create the Barangays Table
        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // The Barangay Name
            $table->timestamps();
        });

        // 2. Add 'barangay_id' to Users table and remove the old string column
        Schema::table('users', function (Blueprint $table) {
            // Add the ID column (nullable first so it doesn't crash existing data)
            $table->foreignId('barangay_id')->nullable()->after('id')->constrained('barangays')->onDelete('set null');
            
            // You can drop the old 'barangay' string column later, 
            // or keep it for a moment while you migrate data.
            // $table->dropColumn('barangay'); 
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['barangay_id']);
            $table->dropColumn('barangay_id');
            //$table->string('barangay')->nullable(); // Add back if rolling back
        });

        Schema::dropIfExists('barangays');
    }
};
