<?php

use App\Models\Customer;
use App\Models\Quote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteRequestsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('quote_requests', function (Blueprint $table) {


            $table->uuid('id')->primary();
            $table->uuid('customer_id')->nullable();
            $table->uuid('quote_id')->nullable();

            $table->boolean('from_web')->default(false);
            $table->json('request_data');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on((new Customer)->getTable());
            $table->foreign('quote_id')->references('id')->on((new Quote())->getTable());

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('quote_requests');
    }
}
