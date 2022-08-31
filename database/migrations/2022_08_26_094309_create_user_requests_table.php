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
        Schema::create('user_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('staff_id');
            $table->text('reason');
            $table->text('month_name');
            $table->uuid('category_id');
            $table->uuid('project_id');
            $table->string('amount');
            $table->boolean('status')->default(false);
            $table->string('reject_reason')->nullable();
            $table->boolean('is_receipt_required')->default(false);
            $table->boolean('file_upload_code')->default(false);
            $table->string('file')->nullable();
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
        Schema::dropIfExists('user_requests');
    }
};
