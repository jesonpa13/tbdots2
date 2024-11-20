<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('province_city');
            $table->string('city');
            $table->string('facility');
            $table->string('address');
            $table->string('head_of_unit');
            $table->string('designation');
            $table->string('contact_number');
            $table->string('email');
            $table->string('intent_upload'); // For storing the path of the uploaded file
            $table->string('assessment_upload'); // For storing the path of the uploaded assessment
            $table->string('status')->default('pending'); // Default status is pending
            $table->timestamps();

            // Foreign key constraint to link to users table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
