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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('no_hp');
            $table->string('jenis_kelamin');
            $table->string('email')->nullable();
            $table->text('alamat');
            $table->integer('usia');
            $table->string('nama_instansi')->nullable();
            $table->string('nip_nim')->nullable();
            $table->foreignId('work_id')->nullable()->constrained('works');
            $table->foreignId('education_id')->nullable()->constrained('educations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
