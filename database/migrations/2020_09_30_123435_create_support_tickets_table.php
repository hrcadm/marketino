<?php

use App\Enum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'support_tickets',
            function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('customer_id')->nullable()->constrained('customers');
                $table->string('title');
                $table->string('status')->default(Enum\SupportTicketStatus::OPEN)->index();
                $table->string('department')->default(Enum\SupportTicketDepartment::GENERAL)->index();
                $table->foreignId('assigned_agent_id')->nullable()->constrained('users');

                $table->timestamps();

                $table->index(['status', 'department']);
                $table->index(['status', 'customer_id', 'department']);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_tickets');
    }
}
