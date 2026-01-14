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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 10, 2);
        $table->enum('type', ['deposit', 'debit']); // টাকা ঢুকলে deposit, খরচ হলে debit
        $table->string('purpose'); // যেমন: 'Apply Fee'
        $table->string('status')->default('completed');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('transactions');
}
};
