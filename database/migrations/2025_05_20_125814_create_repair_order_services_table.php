<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairOrderServicesTable extends Migration
{
    public function up()
    {
        Schema::create('repair_order_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('repair_order_services');
    }
}
?>