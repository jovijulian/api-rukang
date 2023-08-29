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
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('email', 80)->unique();
            $table->string('password');
            $table->string('fullname', 100)->comment("Nama User Sebenarnya");
            $table->string('phone_number', 15)->unique();
            $table->string('address', 100);
            $table->date('birthdate');
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('isActive')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
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
