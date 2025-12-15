<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('veterinarians', function (Blueprint $table) {
            // Add the user_id column and make it a foreign key
            // 'after' places it nicely at the start of the table
            $table->foreignId('user_id')->after('id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('veterinarians', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
