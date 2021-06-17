<?php

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNextStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('next_steps', function (Blueprint $table) {
            $table->id('id');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on((new User)->getTable());

            $table->uuid('customer_id');
            $table->foreign('customer_id')->references('id')->on((new Customer)->getTable());

            $table->date('date');
            $table->string('comment');
            $table->boolean('resolved')->default(FALSE);
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
        Schema::dropIfExists('next_steps');
    }
}
