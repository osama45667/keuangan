<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme_bg_path')->nullable()->after('password');
            $table->enum('theme_overlay', ['light', 'dark', 'auto'])->default('auto')->after('theme_bg_path');
            $table->enum('theme_bg_size', ['cover', 'contain', 'auto'])->default('cover')->after('theme_overlay');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['theme_bg_path', 'theme_overlay', 'theme_bg_size']);
        });
    }
};
