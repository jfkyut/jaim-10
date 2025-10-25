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
        Schema::create('redeems', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->integer('credits');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('status_id')->constrained('redeem_statuses');
            $table->string('claim_through')->nullable(); // for example, gcash, bank transfer, etc.
            // Optional field for claim method
            $table->string('claim_number')->nullable(); // Optional field for claim method, e.g., GCash number, bank account number, etc.
            // This can be used to store the specific claim method details
            // such as GCash number, bank account number, etc.
            // You can also add more fields if needed for additional claim details
            // For example, if you want to store a transaction ID or reference number
            // $table->string('transaction_id')->nullable(); // Optional field for transaction ID
            // $table->string('reference_number')->nullable(); // Optional field for reference number
            // You can add more fields as needed for your specific requirements
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redeems');
    }
};
