<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalInformationTable extends Migration
{
    public function up()
    {
        Schema::create('additional_information', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->string('facility');
            $table->string('province_city');
            $table->string('city');
            $table->string('head_of_unit');
            $table->string('address');
            $table->string('contact_number');
            $table->string('designation');
            $table->string('email');
            $table->timestamps();

            // Foreign key constraint linking to users table
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('additional_information');
    }
}
