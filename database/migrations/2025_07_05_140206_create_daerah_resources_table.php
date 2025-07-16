<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daerahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Kolom untuk nama daerah
            $table->string('provinsi')->nullable(); // Kolom untuk provinsi (opsional)
            $table->text('deskripsi')->nullable(); // Kolom untuk deskripsi (opsional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daerahs');
    }
};