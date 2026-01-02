<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('item_property_id')->nullable();
            $table->string('descrizione');
            $table->unsignedInteger('token');
            $table->unsignedTinyInteger('validity_months')->nullable();
            $table->float('costo')->unsigned();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('active', 'items_active_index');
            $table->foreign('item_property_id', 'items_item_property_id_foreign')
                ->references('id')
                ->on('item_properties')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_properties');
    }
};
