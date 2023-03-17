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
            // reddit oauth doesn't return email. Enforce NOT NULL email by validating the register form submissions
            $table->string('email', MaxLength::USERS_EMAIL)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username')->unique();
            $table->string('password')->nullable();
            $table->boolean('is_admin')->default(false);

            $table->string('github_id')->nullable();
            $table->string('github_token')->nullable();
            $table->string('github_refresh_token')->nullable();

            $table->string('gitlab_id')->nullable();
            $table->string('gitlab_token')->nullable();
            $table->string('gitlab_refresh_token')->nullable();

            $table->string('google_id')->nullable();
            $table->string('google_token')->nullable();
            $table->string('google_refresh_token')->nullable();

            $table->string('reddit_id')->nullable();
            $table->string('reddit_token')->nullable();
            $table->string('reddit_refresh_token')->nullable();

            $table->rememberToken();
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
