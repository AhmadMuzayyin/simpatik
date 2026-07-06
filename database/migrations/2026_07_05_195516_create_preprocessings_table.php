<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('preprocessings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->double('rata_rata_mapel')->default(0);
            $table->double('rata_rata_pengetahuan')->default(0);
            $table->double('rata_rata_keterampilan')->default(0);
            $table->double('rata_rata_sikap')->default(0);
            $table->string('kategori_mapel')->nullable();
            $table->string('kategori_pengetahuan')->nullable();
            $table->string('kategori_keterampilan')->nullable();
            $table->string('kategori_sikap')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preprocessings');
    }
};
