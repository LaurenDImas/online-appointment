<?php

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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(\Illuminate\Support\Facades\DB::raw('(UUID())'));
            $table->foreignId('host_id')->constrained('users')->cascadeOnDelete();
            $table->integer('day')->default(1)->comment('Hari dalam minggu, 1 = Senin, 7 = Minggu');;
            $table->string('time_start', 5);
            $table->string('time_end', 5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
