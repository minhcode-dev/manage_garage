<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate')->unique();
            $table->string('brand');
            $table->string('model');
            $table->year('year')->nullable();
            $table->string('color')->nullable();
            $table->unsignedBigInteger('owner_id')->nullable(); // liên kết với bảng customers
            $table->string('image')->nullable(); // lưu đường dẫn ảnh
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
?>