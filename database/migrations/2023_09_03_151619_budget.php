<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budget', function (Blueprint $table) {
            $table->id();
            $table->date('deadline');
            $table->string('token')->unique();
            $table->integer('hired');
            $table->timestamp('hired_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('payed');
            $table->timestamp('payed_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('finished_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget');
    }
};
