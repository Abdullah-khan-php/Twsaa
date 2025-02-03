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
        Schema::create('snapchat_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('ad_account_id')->constrained('snapchat_ad_accounts')->onDelete('cascade');
            $table->string('campaign_name');
            $table->enum('objective', ['increase_sales', 'increase_store_visits', 'promote_application', 'increase_engagement']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snapchat_campaigns');
    }
};
