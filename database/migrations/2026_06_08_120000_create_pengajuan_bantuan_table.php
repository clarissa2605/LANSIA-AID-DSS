<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pengajuan_bantuan')) {
            return;
        }

        Schema::create('pengajuan_bantuan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lansia_id')
                ->constrained('lansia')
                ->cascadeOnDelete();

            $table->string('jenis')->default('Bantuan Sosial');

            $table->enum('urgensi', ['rendah', 'sedang', 'tinggi'])
                ->default('rendah');

            $table->enum('status', ['pending', 'diproses', 'disalurkan'])
                ->default('pending');

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_bantuan');
    }
};
