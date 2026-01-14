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
        Schema::table('users', function (Blueprint $table) {
            // কলামটি যদি আগে না থাকে তবেই যোগ করবে (Duplicate Error এড়াতে)
            if (!Schema::hasColumn('users', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('users', 'expected_wage')) {
                $table->integer('expected_wage')->default(0);
            }
            if (!Schema::hasColumn('users', 'total_earnings')) {
                $table->decimal('total_earnings', 10, 2)->default(0.00);
            }
            if (!Schema::hasColumn('users', 'location_name')) {
                $table->string('location_name')->nullable();
            }
            if (!Schema::hasColumn('users', 'nid_status')) {
                $table->string('nid_status')->default('pending');
            }
            
            // নতুন Wallet ব্যালেন্স কলাম (যদি না থাকে)
            if (!Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 10, 2)->default(0.00)->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'category', 
                'expected_wage', 
                'total_earnings', 
                'location_name', 
                'nid_status', 
                'balance'
            ]);
        });
    }
};