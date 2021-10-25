<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->smallInteger('term_in_weeks');
            $table->double('interest_rate', 3, 2)->nullable();
            $table->string('description', 255);
            $table->integer('balance')->nullable();
            $table->integer('weekly_payment_amount')->nullable();
            $table->date('last_payment_request')->nullable();
            $table->enum('status', array('processing', 'approved', 'rejected'));
            $table->timestamps();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
