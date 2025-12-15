<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Create the 'barangays' table if it doesn't exist
        if (!Schema::hasTable('barangays')) {
            Schema::create('barangays', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        // 2. Fix the 'users' table
        Schema::table('users', function (Blueprint $table) {
            // Add the new ID column
            if (!Schema::hasColumn('users', 'barangay_id')) {
                $table->foreignId('barangay_id')->nullable()->after('id')->constrained('barangays')->onDelete('set null');
            }

            // DROP THE OLD COLUMN to stop the "Attempt to read property" error
            if (Schema::hasColumn('users', 'barangay')) {
                $table->dropColumn('barangay');
            }
        });
        
        // 3. Fix the 'veterinarians' table (Add user_id)
        Schema::table('veterinarians', function (Blueprint $table) {
            if (!Schema::hasColumn('veterinarians', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        // Reverse operations if needed
        Schema::table('users', function (Blueprint $table) {
            $table->string('barangay')->nullable(); // Add back old column
            $table->dropForeign(['barangay_id']);
            $table->dropColumn('barangay_id');
        });
        Schema::dropIfExists('barangays');
    }
};