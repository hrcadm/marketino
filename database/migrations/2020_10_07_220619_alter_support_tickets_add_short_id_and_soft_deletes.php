<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSupportTicketsAddShortIdAndSoftDeletes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->string('short_id',8)->nullable();
            $table->softDeletes();
        });

        Schema::table('support_messages', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('customer_emails', function (Blueprint $table) {
            $table->uuid('customer_id')->change()->nullable();
        });

        Schema::table('customer_phones', function (Blueprint $table) {
            $table->uuid('customer_id')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn('short_id');
            $table->dropSoftDeletes();
        });

        Schema::table('support_messages', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
