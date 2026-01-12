<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->string('manufacturer');
            $table->decimal('price', 10, 2);
            $table->text('description');
            $table->timestamps();

            $table->index('type');
            $table->index('manufacturer');
            $table->index('price');
        });

        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('attribute_key');
            $table->string('string_value')->nullable();
            $table->decimal('numeric_value', 12, 4)->nullable();
            $table->timestamps();

            $table->index(['product_id', 'attribute_key']);
            $table->index(['attribute_key', 'string_value']);
            $table->index(['attribute_key', 'numeric_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('products');
    }
};
