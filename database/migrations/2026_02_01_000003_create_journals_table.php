<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nomor_jurnal', 30)->unique();
            $table->foreignId('period_id')->constrained('accounting_periods');
            $table->string('deskripsi')->nullable();
            $table->string('reference_no')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['tanggal', 'period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
