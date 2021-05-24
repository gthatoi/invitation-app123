<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->unique();
            $table->string('title');
            $table->text('description');
            $table->string('meeting_link');
            $table->date('scheduled_date');
            $table->jsonb('scheduled_time');
            $table->boolean('is_cancelled')->default(false);
            $table->integer('organizer_id')->unsigned();
            $table->timestamps();

            $table->foreign('organizer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
