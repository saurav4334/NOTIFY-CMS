<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Named packaged plans (Enterprise / promotional bundles) shown on the pricing page.
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('price_suffix')->nullable();   // e.g. "/month", "/SMS"
            $table->json('features')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_link')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
