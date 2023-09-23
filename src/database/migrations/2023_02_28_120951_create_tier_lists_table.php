<?php

use App\Models\TierList;
use App\Models\User;
use Database\Helpers\MaxLength;
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
        Schema::create(TierList::TABLE, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', MaxLength::TIER_LISTS_TITLE);
            $table->json('data');
            $table->text('description')->nullable();
            $table->text('thumbnail');
            $table->boolean('is_public')->default(false)->index();
            $table->timestamps();

            $table->foreignUuid(User::FOREIGN_KEY)->references('id')->on(User::TABLE)->cascadeOnDelete();
            // $table->foreignUuid(Categories::FOREIGN_KEY)->references('id')->on(Categories::TABLE);
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
