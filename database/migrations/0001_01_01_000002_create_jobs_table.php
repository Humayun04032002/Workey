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
        // Jobs Table Update
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('category');
            $table->decimal('wage', 10, 2);
            $table->enum('wage_type', ['daily', 'hourly']); // মজুরি দিন না ঘণ্টা হিসেবে
            
            // পেমেন্ট মেথড (নতুন)
            $table->enum('payment_type', ['cash', 'in_app'])->default('cash'); // হাতে হাতে নাকি ওয়ালেটে
            
            // তারিখ ও সময়
            $table->date('work_date'); 
            $table->time('start_time'); 
            $table->time('end_time'); 
            $table->string('duration')->nullable(); 
            $table->integer('worker_count')->default(1); 
            
            $table->text('description')->nullable();
            $table->string('location_name');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->string('status')->default('open'); // open, filled, completed
            $table->timestamps();
        });

        // Applications Table Update
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('worker_id')->constrained('users')->onDelete('cascade');
            
            // স্ট্যাটাস লজিক আপডেট
            // pending, accepted, arrived, payment_pending, rejected, completed
            $table->string('status')->default('pending'); 
            
            // নতুন কলামসমূহ ট্র্যাকিংয়ের জন্য
            $table->timestamp('arrived_at')->nullable();   // কর্মী কর্মস্থলে পৌঁছানোর সময়
            $table->timestamp('completed_at')->nullable(); // কাজ বা পেমেন্ট পুরোপুরি সম্পন্ন হওয়ার সময়
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
        Schema::dropIfExists('jobs');
    }
};