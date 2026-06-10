<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Per-page SEO. Mirrors cms.js `seo` and adds Open Graph / schema fields (PRD §9).
        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->string('page')->unique();        // home, about, services, pricing, calculator, faq, contact
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->json('schema')->nullable();      // JSON-LD schema markup
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
    }
};
