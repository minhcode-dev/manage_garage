
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('parts')) {

        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit')->nullable(); // đơn vị như "cái", "lít", "kg"
            $table->string('code')->unique(); // Mã phụ tùng (mã riêng, dễ tìm)
            $table->text('description')->nullable(); // Mô tả
            $table->integer('stock')->default(0); // Số lượng tồn kho
            $table->decimal('price', 12, 2)->default(0); // Giá bán hoặc giá nhập
            $table->timestamps();
        });
    }
    }

    public function down()
    {
        Schema::dropIfExists('parts');
    }
}
?>