<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Trusted-client logos. Mirrors cms.js `clients` (icon/colour) and supports
        // uploaded logo images as an alternative to Font Awesome icons.
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();     // Font Awesome class, e.g. fa-shopping-cart
            $table->string('color')->nullable();    // text colour
            $table->string('bg')->nullable();       // CSS gradient/background
            $table->string('logo')->nullable();     // uploaded image path (optional)
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
