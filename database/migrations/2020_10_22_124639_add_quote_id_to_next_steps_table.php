<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Quote;

class AddQuoteIdToNextStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('next_steps', function (Blueprint $table) {
            $table->uuid('quote_id')->after('customer_id')->nullable();
            $table->foreign('quote_id')->references('id')->on((new Quote())->getTable());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('next_steps', function (Blueprint $table) {
            $table->dropColumn('quote_id');
        });
    }
}
