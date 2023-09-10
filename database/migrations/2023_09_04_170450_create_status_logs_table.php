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
        Schema::create('status_logs', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->string('product_id')->nullable();
            $table->bigInteger('status_id')->unsigned()->nullable();
            $table->string('status_name', 20)->comment("Status Proses")->nullable();
            $table->timestamp('status_date');
            $table->string('status_photo', 255);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('deleted_by', 40)->nullable();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_logs');
    }
};
