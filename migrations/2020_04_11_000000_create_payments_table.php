<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id')->index()->nullable();
            $table->morphs('payable');
            $table->unsignedDouble('amount', 2)->default(0.00);
            $table->unsignedDouble('paid_amount')->default(0);
            $table->string('description');
            $table->string('transaction_id')->index();
            $table->string('currency')->index()->default('CNY');
            $table->string('status')->index()->default('pending')->comment('pending/paid/cancelled/failed');
            $table->string('gateway')->index();
            $table->json('gateway_order')->nullable();
            $table->json('context')->nullable();
            $table->json('original_result')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
