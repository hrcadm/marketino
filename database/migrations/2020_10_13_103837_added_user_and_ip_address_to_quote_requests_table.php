<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedUserAndIpAddressToQuoteRequestsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('quote_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->foreign('user_id')->references('id')->on((new User)->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('quote_requests', function (Blueprint $table) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('ip_address');
                $table->dropColumn('user_id');

            });

        });
    }
}
