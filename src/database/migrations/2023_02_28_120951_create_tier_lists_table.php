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
        Schema::create('tier_lists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 40);
            $table->json('data');
            $table->text('description')->nullable();
            $table->boolean('public')->default(false)->index();
            $table->timestamps();

            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tier_lists');
    }
};
