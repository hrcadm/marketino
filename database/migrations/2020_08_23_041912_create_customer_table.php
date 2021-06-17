<?php

use App\Enum\CompanyType;
use App\Enum\CountryCode;
use App\Enum\CustomerStatus;
use App\Enum\SaleChannel;
use App\Models\ActivityType;
use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create((new Customer)->getTable(), function(Blueprint $table) {
            $table->uuid('id')->primary();

            //main
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('vat_number', 11)->nullable();///partita iva
            $table->string('fiscal_number', 16)->nullable();///codice fiscale

            $table->string('address')->nullable();
            $table->string('address_additional')->nullable();

            //todo::dropdown lista za zip (koji je povezano sa city, region, country_code)
            $table->string('zip', 5)->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country_code')->nullable()->default(CountryCode::IT);

            $table->string('note')->nullable();
            //checkboxovi
            $table->boolean('gdpr')->default(false);
            $table->boolean('newsletter')->default(false);

            //prodajne stvari
            $table->string('sale_channel')->default(SaleChannel::PHONE);

            //$table->integer('activity_type_id')->nullable();//todo:: fk
            $table->uuid('activity_type_id')->nullable();//todo:: fk
            $table->foreign('activity_type_id')->references('id')->on((new ActivityType)->getTable());
            $table->string('customer_status')->default(CustomerStatus::LEAD);
            $table->string('einvoice_code')->nullable();// SDI || PEC


            //podaci za trajni nalog
            $table->string('iban')->nullable();
            $table->string('iban_name')->nullable();//Titolare Conto ,ime na koga glasi iban

            //GRENKE PODACI:
            $table->string('company_type')->default(CompanyType::INDIVIDUAL);
            $table->string('company_date')->nullable();///mjesec i godina osnivanja firme
            $table->string('legal_contact')->nullable();//Rappresentante Legale
            //todo::2 attachmenta : slika osobne slika poreznog broja

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists((new Customer())->getTable());
    }
}
