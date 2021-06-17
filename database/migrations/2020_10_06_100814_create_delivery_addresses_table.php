<?php

use App\Enum\CountryCode;
use App\Models\Customer;
use App\Models\DeliveryAddress;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new DeliveryAddress)->getTable(), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();

            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('address_additional')->nullable();
            //todo::dropdown lista za zip (koji je povezano sa city, region, country_code)
            $table->string('zip', 5)->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country_code')->nullable()->default(CountryCode::IT);
            $table->string('note')->nullable();


            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customer_id')->references('id')->on((new Customer)->getTable());

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists((new DeliveryAddress)->getTable());
    }
}
