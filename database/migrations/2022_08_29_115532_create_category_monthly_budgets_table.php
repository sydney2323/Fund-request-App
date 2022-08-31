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
        Schema::create('category_monthly_budgets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('monthly_budget_id');
            $table->uuid('category_id');
            $table->string('amount');
            $table->foreign('monthly_budget_id')
            ->references('id')->on('monthly_budgets')->onDelete('cascade');
            $table->foreign('category_id')
            ->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('category_monthly_budgets');
    }
};
