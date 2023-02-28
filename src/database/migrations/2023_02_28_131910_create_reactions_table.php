<?php

use App\Models\TierList;
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
        Schema::create('reactions', function (Blueprint $table) {
            $table->comment('Maintians data about likes and dislikes of public tier lists.');
            $table->boolean('like');
            $table->boolean('dislike');
            $table->timestamps();

            $table->foreignUuid(User::FOREIGN_KEY)->constrained()->cascadeOnDelete();
            $table->foreignUuid(TierList::FOREIGN_KEY)->constrained()->cascadeOnDelete();
            $table->primary([User::FOREIGN_KEY, Tierlist::FOREIGN_KEY]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
