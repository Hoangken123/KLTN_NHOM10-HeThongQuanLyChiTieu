<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();;
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->unique()->nullable();
            $table->string('fullName')->unique()->nullable();
            $table->string('role')->default('user');
            $table->boolean('isActived')->default(false);
            $table->boolean('isBlocked')->default(false);
            $table->string('address')->unique()->nullable();
            $table->double('monthly_income')->default(0);
            $table->double('monthly_customer_spending')->default(0);
            $table->string('avatar')->nullable();
            $table->string('currency', 10)->default('VND');
            $table->string('verify_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
