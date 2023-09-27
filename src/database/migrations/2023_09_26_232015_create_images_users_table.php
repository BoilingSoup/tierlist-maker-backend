<?php

use App\Models\Image;
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
        Schema::create('images_users', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid(User::FOREIGN_KEY)->references('id')->on(User::TABLE);
            $table->foreignUuid(Image::FOREIGN_KEY)->references('id')->on(Image::TABLE)->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images_users');
    }
};
