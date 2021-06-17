<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTicketTicketTagPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_ticket_ticket_tag', function (Blueprint $table) {
            $table->id();
            $table->uuid('ticket_id');
            $table->foreignId('tag_id')->constrained('ticket_tags');

            $table->foreign('ticket_id')->references("id")->on("support_tickets");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_ticket_ticket_tag');
    }
}
