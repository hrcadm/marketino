<?php

use App\Enum\SaleItemType;
use App\Enum\SaleVat;
use App\Models\SaleItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new SaleItem)->getTable(), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('vat')->default(SaleVat::VAT_22);
            $table->string('type')->default(SaleItemType::PHYSICAL_PRODUCT);
            //$table->string('weight')->nullable();
            //$table->string('dimensions')->nullable();
            $table->string('description')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists((new SaleItem)->getTable());
    }
}
