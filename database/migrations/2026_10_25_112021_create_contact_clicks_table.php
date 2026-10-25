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
        Schema::create('contact_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('channel');
            $table->string('page_url', 2048);
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->timestamp('line_notified_at')->nullable();
            $table->timestamps();

            $table->index('created_at');
            $table->index(['channel', 'created_at']);
            $table->index(['ip_address', 'channel', 'line_notified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_clicks');
    }
};
