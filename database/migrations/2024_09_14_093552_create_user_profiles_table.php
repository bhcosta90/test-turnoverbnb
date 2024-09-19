<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_profile', function (Blueprint $table) {
            $table->foreignId('id')->primary()->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('balance');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profile');
    }
};
