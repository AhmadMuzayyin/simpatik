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
        Schema::create('nilai_mapels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mata_pelajarans')->cascadeOnDelete();
            $table->float('nilai')->default(0);
            $table->timestamps();
            
            // Unik per siswa per mapel
            $table->unique(['siswa_id', 'mapel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_mapels');
    }
};
