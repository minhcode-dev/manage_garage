<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\EmployeeAuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RepairOrderController;

Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('report.index');
Route::get('/report/pdf', [App\Http\Controllers\ReportController::class, 'exportPdf'])->name('report.pdf');

// Routes public - không cần đăng nhập
Route::get('login', [EmployeeAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [EmployeeAuthController::class, 'login']);
Route::get('/parts/pdf', [PartController::class, 'exportPdf'])->name('parts.export.pdf');
Route::patch('/repair_orders/{order}/update_status', [RepairOrderController::class, 'updateStatus'])->name('repair_orders.update_status');
Route::patch('/repair_orders/{order}/update_payment_status', [RepairOrderController::class, 'updatePaymentStatus'])->name('repair_orders.update_payment_status');
Route::put('repair-orders/{id}', [RepairOrderController::class, 'update'])->name('repair_orders.update');
Route::delete('repair-orders/{id}', [RepairOrderController::class, 'destroy'])->name('repair_orders.destroy');
// Logout route
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Routes cần đăng nhập mới truy cập được
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('parts', PartController::class);
    Route::resource('employees', EmployeeController::class);
    Route::get('/repair_orders', [RepairOrderController::class, 'index'])->name('repair_orders.index');
    Route::get('repair-orders/create', [RepairOrderController::class, 'create'])->name('repair_orders.create');
    Route::post('/repair-orders/store', [RepairOrderController::class, 'store'])->name('repair_orders.store');
});
?>