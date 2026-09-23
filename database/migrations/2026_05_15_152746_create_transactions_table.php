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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers');

            $table->foreignId('media_id')
                ->constrained('media');

            $table->foreignId('sub_categorie_id')
                ->constrained('sub_categories');

            $table->foreignId('service_id')
                ->constrained('services');

            $table->foreignId('purpose_id')
                ->constrained('purposes');

            $table->text('data_diminta');

            // Status transaksi
            $table->string('status_transaksi')
                ->default('Belum dilayani');

            // Data penyelesaian pelayanan
            $table->string('bukti_pelayanan')
                ->nullable();

            $table->text('keterangan_pelayanan')
                ->nullable();

            $table->timestamp('dilayani_pada')
                ->nullable();

            $table->foreignId('dilayani_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Status penilaian
            $table->string('status_penilaian')
                ->default('Belum menilai');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};