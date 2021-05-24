<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInviteesTable extends Migration
{
    public function up()
    {
        Schema::create('user_invitees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('invitation_id')->unsigned();
            $table->string('status');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('invitation_id')->references('id')->on('invitations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_invitees');
    }
}
