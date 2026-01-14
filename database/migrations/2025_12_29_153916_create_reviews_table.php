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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('employer_id')->constrained('users')->onDelete('cascade');
            
            // নতুন কলাম: কোন কাজের বিপরীতে রিভিউ দেওয়া হচ্ছে
            $table->foreignId('job_id')->nullable()->constrained('jobs')->onDelete('cascade');
            
            $table->integer('rating')->default(5); // ১ থেকে ৫ এর মধ্যে
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};