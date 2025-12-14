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
        Schema::create('music', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('generated_by');
            $table->date('generated_at');
            $table->longText('description')->nullable();
            $table->longText('lyrics')->nullable();
            $table->enum('status', [
                'pending',
                'under review',
                'approved',
                'rejected'
            ])->default('pending');
            $table->longText('review_comments')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->foreignId('album_id')->nullable()->constrained('albums')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music');
    }
};
