<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'employee_id',
        'date',
        'total_amount',
        'status',
        'notes',
        'payment_method',
        'repair_status',
        'payment_status',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class,'repair_order_service')->withPivot('quantity', 'price');
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class,'repair_order_part')->withPivot('quantity', 'price');
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