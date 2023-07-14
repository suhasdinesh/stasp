<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_type', function (Blueprint $table) {
            $table->id();
            $table->string('fee_type');
            $table->timestamps();
        });
        
        Schema::create('fees',function (Blueprint $table) {
            $table->id();
            $table->string('fee_name');
            $table->foreignId('fee_type')->references('id')->on('fee_type');
            $table->double('amount');
            $table->foreignId('student_id')->references('id')->on('student');
            $table->timestamps();
        });

        Schema::create('fee_student',function (Blueprint $table){
            $table->foreignId('fees_id')->references('id')->on('fees');
            $table->foreignId('student_id')->references('id')->on('student');
            $table->double('amount');
            $table->double('discount')->nullable();
            $table->double('scholarships')->nullable();
            $table->double('balance')->nullable();
            $table->timestamps();
        });

        Schema::create('transaction',function(Blueprint $table){
            $table->id();
            $table->date('t_date');
            $table->foreignId('student_id')->references('id')->on('student');
            $table->foreignId('fee_id')->references('id')->on('fees');
            $table->double('t_amount');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_type');
        Schema::dropIfExists('fees');
        Schema::dropifExists('fee_student');
        Schema::dropifExists('transaction');

    }
}
