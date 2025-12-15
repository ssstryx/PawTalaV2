<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->foreignId('veterinarian_id')->nullable()->constrained('veterinarians')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropForeign(['veterinarian_id']);
            $table->dropColumn('veterinarian_id');
        });
    }
};
