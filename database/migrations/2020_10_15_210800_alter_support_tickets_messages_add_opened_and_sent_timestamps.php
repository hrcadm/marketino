<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSupportTicketsMessagesAddOpenedAndSentTimestamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'support_tickets',
            function (Blueprint $table) {
                $table->timestamp('first_opened_at')->nullable();
            }
        );

        Schema::table(
            'support_messages',
            function (Blueprint $table) {
                $table->boolean('send_to_customer')->default(false);
                $table->timestamp('sent_to_customer_at')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'support_tickets',
            function (Blueprint $table) {
                $table->dropColumn('first_opened_at');
            }
        );

        Schema::table(
            'support_messages',
            function (Blueprint $table) {
                $table->dropColumn('send_to_customer');
                $table->dropColumn('sent_to_customer_at');
            }
        );
    }
}
