<?php

use App\Enum\SaleVat;
use App\Models\Quote;
use App\Models\SaleItem;
use App\Models\SaleItemPrice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_items', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->uuid('quote_id');
            $table->uuid('sale_item_price_id');

            $table->integer('quantity', false, true);
            $table->decimal('total_discount_amount', 10, 2, true)->default(0);
            $table->decimal('total_net_amount_before_discount', 10, 2, true);
            $table->decimal('total_net_amount', 10, 2, true);

            $table->string('note')->nullable();

            $table->foreign('quote_id')->references('id')->on((new Quote())->getTable());
            $table->foreign('sale_item_price_id')->references('id')->on((new SaleItemPrice())->getTable());

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
        Schema::dropIfExists('quote_items');
    }
}
