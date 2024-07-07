<?php

use App\Models\Hall;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Hall::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('sid')->unique();
            $table->string('name');
            $table->string('email')
                ->nullable()
                ->unique();
            $table->string('phone')->unique();
            $table->string('address')->nullable();
            $table->string('block_no')->nullable();
            $table->string('room_no')->nullable();
            $table->string('department');
            $table->string('session');
            $table->string('year');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
