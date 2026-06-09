<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Flexible JSON blocks for homepage areas: hero, why_us, cta, pricing_teaser.
        // Mirrors cms.js `hero` and `whyUs`.
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section')->unique();   // hero, why_us, cta, pricing_teaser
            $table->json('content')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
