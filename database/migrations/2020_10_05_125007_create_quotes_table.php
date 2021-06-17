<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->uuid('customer_id');
            $table->json('customer_data');

            $table->increments('number');
            $table->dropPrimary("table_number_primary");

            $table->integer('control_number', false, true)->default(0);

            $table->decimal('total_amount', 10, 2, true);
            $table->decimal('total_net_amount', 10, 2, true);
            $table->decimal('total_vat_amount', 10, 2, true);

            $table->string('note')->nullable();

            $table->date('valid_until');

            $table->foreign('customer_id')->references('id')->on((new Customer)->getTable());

            $table->timestamps();
            $table->softDeletes();

        });

        \DB::statement('ALTER SEQUENCE quotes_number_seq RESTART WITH 1000;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotes');
    }
}
