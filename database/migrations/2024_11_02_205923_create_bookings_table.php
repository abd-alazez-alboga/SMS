<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('number_of_passengers');
            $table->unsignedInteger('number_of_bags_of_wieght_10');
            $table->unsignedInteger('number_of_bags_of_wieght_23');
            $table->unsignedInteger('number_of_bags_of_wieght_30');
            $table->date('date');
            $table->enum('vehicle', ['Car', 'Van', 'Car or Van']);
            $table->string('name');
            $table->enum('entry_requirement', ['Visa', 'Foreign Passport', 'Residency', 'E-Visa']);
            $table->longText('passport_photo');
            $table->longText('ticket_photo');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
