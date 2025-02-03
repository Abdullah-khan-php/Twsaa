<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('x_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string("campaign_id")->nullable();
            $table->string("name");
            $table->enum("objective", ["INCREASE_SALES","INCREASE_STORE_VISITS","PROMOTE_APP","INCREASE_ENGAGEMENT"]);
            $table->enum("status",["AWARENESS", "TRAFFIC", "ENGAGEMENT", "LEAD_GENERATION", "SALES", "APP_PROMOTION"]);
            $table->date("start_date");
            $table->date("end_date");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compaigns');
    }
};
