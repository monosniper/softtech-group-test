<?php

use App\Models\Client;
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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Client::class)->comment('Клиент')->constrained()->cascadeOnDelete();
            $table->string('file_unique_id')->nullable()->comment('Внешн. уник. ID');
            $table->string('type')->comment('Тип');
            $table->bigInteger('size_bytes')->comment('Размер (байт)');
            $table->string('name')->comment('Имя');
            $table->string('extension')->comment('Расширение');
            $table->string('mime')->comment('Mime Тип');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
