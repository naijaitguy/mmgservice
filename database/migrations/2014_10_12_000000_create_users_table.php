<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('roles', function(Blueprint $table){

            $table->id();
            $table->string('role');
            $table->timestamps();

        });
        Schema::create('wallettypes', function(Blueprint $table){

            $table->id();
            $table->string('name');

            $table->timestamps();

        });


        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('wallets',function(Blueprint $table){

            $table->id();
            $table->string('name');
            $table->integer('mini_balance');
            $table->integer('balance');
            $table->integer('interest_rate');
            $table->unsignedBigInteger('wallettype_id');
            $table->foreign('wallettype_id')->references('id')->on('wallettypes')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('transrecords', function(Blueprint $table){

            $table->id();

            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('reciever_id');
            $table->foreign('reciever_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('trans_reference');
            $table->integer('transamount');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('locations', function(Blueprint $table){


            $table->id();
            $table->string('lga');
            $table->string('state');

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        schema::dropIfExist('roles');


    }
};
