<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepairOrderPartsTable extends Migration
{
    public function up()
    {
        Schema::create('repair_order_part', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('part_id');
            // các cột khác ví dụ:
            $table->unsignedBigInteger('repair_order_id'); // giả sử cũng liên kết với bảng repair_orders
            $table->integer('quantity')->default(1);
        
            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
            $table->foreign('repair_order_id')->references('id')->on('repair_orders')->onDelete('cascade');
        
            $table->timestamps();
        });
        
    }
    public function down()
    {
        Schema::dropIfExists('repair_order_part');
    }
}
?>