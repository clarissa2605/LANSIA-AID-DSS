<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add missing foreign key constraints for data integrity
     */
    public function up(): void
    {
        // Add foreign key to penilaian.lansia_id if not exists
        if (Schema::hasTable('penilaian') && Schema::hasColumn('penilaian', 'lansia_id')) {
            Schema::table('penilaian', function (Blueprint $table) {
                // Check if constraint doesn't already exist
                try {
                    $table->foreign('lansia_id')
                        ->references('id')
                        ->on('lansia')
                        ->cascadeOnDelete();
                } catch (\Exception $e) {
                    // Constraint might already exist
                }
            });
        }

        // Add foreign key to penilaian.kriteria_id if not exists
        if (Schema::hasTable('penilaian') && Schema::hasColumn('penilaian', 'kriteria_id')) {
            Schema::table('penilaian', function (Blueprint $table) {
                try {
                    $table->foreign('kriteria_id')
                        ->references('id')
                        ->on('kriteria')
                        ->cascadeOnDelete();
                } catch (\Exception $e) {
                    // Constraint might already exist
                }
            });
        }

        // Add foreign keys to perbandingan_kriteria
        if (Schema::hasTable('perbandingan_kriteria')) {
            Schema::table('perbandingan_kriteria', function (Blueprint $table) {
                try {
                    $table->foreign('kriteria_1_id')
                        ->references('id')
                        ->on('kriteria')
                        ->cascadeOnDelete();
                } catch (\Exception $e) {
                    // Constraint might already exist
                }

                try {
                    $table->foreign('kriteria_2_id')
                        ->references('id')
                        ->on('kriteria')
                        ->cascadeOnDelete();
                } catch (\Exception $e) {
                    // Constraint might already exist
                }
            });
        }

        // Update pengajuan_bantuan to prevent orphaned records
        if (Schema::hasTable('pengajuan_bantuan') && Schema::hasColumn('pengajuan_bantuan', 'lansia_id')) {
            Schema::table('pengajuan_bantuan', function (Blueprint $table) {
                try {
                    // Change behavior: instead of cascadeOnDelete, restrict deletion
                    // This will prevent accidental deletion of lansia with active requests
                    $table->dropForeign('pengajuan_bantuan_lansia_id_foreign');
                } catch (\Exception $e) {
                    // Foreign key might not exist yet
                }

                try {
                    $table->foreign('lansia_id')
                        ->references('id')
                        ->on('lansia')
                        ->restrictOnDelete(); // Prevent deletion if has active pengajuan
                } catch (\Exception $e) {
                    // Constraint might already exist
                }
            });
        }
    }

    /**
     * Revert changes
     */
    public function down(): void
    {
        if (Schema::hasTable('penilaian')) {
            Schema::table('penilaian', function (Blueprint $table) {
                try {
                    $table->dropForeign('penilaian_lansia_id_foreign');
                } catch (\Exception $e) {
                    // Ignore if doesn't exist
                }
            });

            Schema::table('penilaian', function (Blueprint $table) {
                try {
                    $table->dropForeign('penilaian_kriteria_id_foreign');
                } catch (\Exception $e) {
                    // Ignore if doesn't exist
                }
            });
        }

        if (Schema::hasTable('perbandingan_kriteria')) {
            Schema::table('perbandingan_kriteria', function (Blueprint $table) {
                try {
                    $table->dropForeign('perbandingan_kriteria_kriteria_1_id_foreign');
                } catch (\Exception $e) {
                    // Ignore if doesn't exist
                }

                try {
                    $table->dropForeign('perbandingan_kriteria_kriteria_2_id_foreign');
                } catch (\Exception $e) {
                    // Ignore if doesn't exist
                }
            });
        }

        if (Schema::hasTable('pengajuan_bantuan')) {
            Schema::table('pengajuan_bantuan', function (Blueprint $table) {
                try {
                    $table->dropForeign('pengajuan_bantuan_lansia_id_foreign');
                } catch (\Exception $e) {
                    // Ignore if doesn't exist
                }
            });
        }
    }
};
