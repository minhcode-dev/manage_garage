<?php
// app/Models/RepairOrderPart.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairOrderPart extends Model
{
    protected $fillable = ['repair_order_id', 'part_id', 'quantity', 'price'];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
?>