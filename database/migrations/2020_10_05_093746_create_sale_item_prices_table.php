<?php

use App\Models\SaleItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleItemPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_item_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sale_item_id');
            $table->foreign('sale_item_id')->references('id')->on((new SaleItem())->getTable());

            $table->boolean('is_default')->default(false);

            $table->string('name');
            $table->decimal('net_price', 10, 2, true);
            $table->decimal('original_net_price', 10, 2, true)->nullable();

            $table->string('discount_name')->nullable();
            $table->decimal('discount_amount', 10, 2, true)->nullable();

            $table->string('note')->nullable();
            $table->string('description')->nullable();

            $table->string('_data')->nullable();//instruction for controller, e.g. shippment fee required

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
        Schema::dropIfExists('sale_item_prices');
    }
}
