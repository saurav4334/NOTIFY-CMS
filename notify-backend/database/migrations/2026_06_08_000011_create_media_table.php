<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  // original filename
            $table->string('path');                  // storage path
            $table->string('disk')->default('public');
            $table->string('mime_type')->nullable();
            $table->string('extension', 16)->nullable();
            $table->unsignedBigInteger('size')->default(0); // bytes
            $table->string('folder')->default('uploads')->index();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
