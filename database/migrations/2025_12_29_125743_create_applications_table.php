<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ড্রপ করার আগে সাবধান: এতে আপনার আগের সব অ্যাপ্লিকেশনের ডাটা মুছে যাবে
        Schema::dropIfExists('applications'); 
        
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('worker_id')->constrained('users')->onDelete('cascade');
            
            // এখানে 'completed' এবং 'cancelled' স্ট্যাটাস যোগ করা হয়েছে
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed', 'cancelled'])->default('pending');
            
            // কর্মী ট্র্যাকিং কলামগুলো যোগ করা হয়েছে
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};