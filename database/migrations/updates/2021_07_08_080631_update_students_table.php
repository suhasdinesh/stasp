<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('student',function(Blueprint $table){
            $table->string('university_reg_no');
            $table->text('photo');
            $table->date('dob');
            $table->text('address');
            $table->string('p_g_name'); //Parent/Guardian Name
            $table->text('p_g_address'); // Parent/Guardian Address
            $table->unsignedBigInteger('p_g_phone'); // Parent/Guardian Phone
            $tavle->foreignId('class_id')->references('class')->on('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
