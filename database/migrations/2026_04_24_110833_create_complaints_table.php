<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('category_id')->constrained()->cascadeOnDelete();

    $table->string('title');
    $table->text('description');
    $table->string('location')->nullable();

    // ✅ INI WAJIB
    $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditolak'])
          ->default('menunggu');

    $table->string('image')->nullable();
    $table->boolean('is_anonymous')->default(false);

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
