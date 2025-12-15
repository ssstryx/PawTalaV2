<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Who did the action
            $table->string('user_name')->nullable(); // Snapshot of name
            $table->string('role')->nullable(); // Snapshot of role
            $table->string('action'); // e.g., "Created User"
            $table->text('details')->nullable(); // e.g., "Created admin account for John Doe"
            $table->string('ip_address')->nullable();
            $table->timestamps();

            // Foreign key (optional, if you want logs to disappear when user is deleted)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};