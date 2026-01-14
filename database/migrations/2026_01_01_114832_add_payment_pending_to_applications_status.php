<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    DB::statement("ALTER TABLE applications MODIFY COLUMN status ENUM('pending', 'accepted', 'rejected', 'payment_pending', 'completed') DEFAULT 'pending'");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications_status', function (Blueprint $table) {
            //
        });
    }
};
