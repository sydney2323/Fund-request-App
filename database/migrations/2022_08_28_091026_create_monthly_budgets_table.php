<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_budgets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('month');
            $table->string('month_name');
            $table->string('budget_amount');
            $table->string('carry_over');
            $table->boolean('category_added')->default(false);
            $table->string('ending_at');
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
        Schema::dropIfExists('monthly_budgets');
    }
};
