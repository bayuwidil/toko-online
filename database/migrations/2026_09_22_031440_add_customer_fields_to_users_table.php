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
        Schema::table('users', function (Blueprint $table) {

            $table->timestamp('phone_verified_at')
                ->nullable()
                ->after('phone');

            $table->string('province')
                ->nullable();

            $table->string('city')
                ->nullable();

            $table->string('district')
                ->nullable();

            $table->string('subdistrict')
                ->nullable();

            $table->string('postal_code', 10)
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {


            $table->dropColumn([
                'phone',
                'phone_verified_at',
                'address',
                'province',
                'city',
                'district',
                'subdistrict',
                'postal_code',
            ]);
        });
    }
};
