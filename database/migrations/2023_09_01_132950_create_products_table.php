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
        Schema::create('products', function (Blueprint $table) {
            $table->string('id', 40)->primary();
            $table->bigInteger('segment_id')->unsigned()->nullable();
            $table->string('segment_name', 50)->comment("Nama Segmen")->nullable();
            $table->string('barcode', 100)->unique();
            $table->string('module_number', 20)->comment("Nomor Modul")->nullable();
            $table->string('bilah_number', 5)->comment("Nomor Bilah")->nullable();
            $table->date('date')->nullable();
            $table->string('shelf_number', 20)->comment("Nomor Rak")->nullable();
            $table->boolean('"1/0"')->nullable();
            $table->boolean('baut_mur')->comment("B1 sampai B10")->nullable();
            $table->bigInteger('description_id')->unsigned()->nullable();
            $table->text('description')->comment("Keterangan")->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('image', 100)->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->text('category')->comment("Kategori")->nullable();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('deleted_by', 40)->nullable();
            $table->boolean('deleted_flag')->default(false)->nullable();
            $table->bigInteger('process_id')->unsigned()->nullable();
            $table->string('process_name', 20)->comment("Status Proses")->nullable();
            $table->timestamp('status_date');
            $table->string('process_attachment', 100)->nullable();

            $table->foreign('segment_id')->references('id')->on('segments');
            $table->foreign('description_id')->references('id')->on('descriptions');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('process_id')->references('id')->on('processes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
