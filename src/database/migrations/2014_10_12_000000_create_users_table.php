<?php

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
        Schema::create(User::TABLE, function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email', MaxLength::USERS_EMAIL)->unique();
            $table->string('username', MaxLength::USERS_USERNAME)->unique();
            $table->string('password', MaxLength::USERS_PASSWORD);
            $table->boolean('is_admin');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
