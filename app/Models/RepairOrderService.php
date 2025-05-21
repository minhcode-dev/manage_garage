<?php
// app/Models/RepairOrderService.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairOrderService extends Model
{
    protected $fillable = ['repair_order_id', 'service_id', 'quantity', 'price'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
?>