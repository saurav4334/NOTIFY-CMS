<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SMS slab pricing. Mirrors cms.js `pricingNM` (non-masking) and `pricingM` (masking).
        // Feeds both the pricing page and the calculator.
        Schema::create('sms_rates', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['non_masking', 'masking']);
            $table->string('tier');                       // Starter, Business, Enterprise, Elite
            $table->unsignedInteger('min_qty');
            $table->unsignedInteger('max_qty')->nullable(); // null = no upper bound
            $table->decimal('price', 8, 4);               // price per SMS
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_rates');
    }
};
