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
        \Illuminate\Support\Facades\DB::statement('SET SESSION sql_require_primary_key=0');
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('email', 80)->unique();
            $table->string('password');
            $table->string('fullname', 100)->comment("Nama User Sebenarnya");
            $table->string('phone_number', 15)->unique();
            $table->string('address', 100);
            $table->date('birthdate');
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->string('group_name', 100)->comment("Nama Kelompok")->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('isActive')->default(false);
            $table->rememberToken();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->boolean('isAdmin')->default(false);

            $table->foreign('group_id')->references('id')->on('groups');
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
