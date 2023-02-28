<?php

use App\Models\User;
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
            $table->string('title', MaxLength::TIER_LISTS_TITLE);
            $table->json('data');
            $table->text('description')->nullable();
            $table->boolean('public')->default(false)->index();
            $table->timestamps();

            $table->foreignUuid(User::FOREIGN_KEY)->constrained()->cascadeOnDelete();
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
