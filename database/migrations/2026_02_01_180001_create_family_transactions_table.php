<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('family_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('type', ['income', 'expense']);
            $table->foreignId('category_id')->constrained('family_categories');
            $table->string('member_name')->nullable();
            $table->decimal('amount', 18, 2);
            $table->string('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['tanggal', 'type', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_transactions');
    }
};
