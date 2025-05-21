<?php
// app/Models/RepairOrder.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairOrder extends Model
{
    protected $fillable = [
        'customer_id', 'vehicle_id', 'employee_id', 'date', 'total_amount', 'status', 'notes'
    ];

    public function services()
{
    return $this->belongsToMany(Service::class)->withPivot('quantity', 'price');
}

public function parts()
{
    return $this->belongsToMany(Part::class)->withPivot('quantity', 'price');
}


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
?>