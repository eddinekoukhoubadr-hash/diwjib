<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
Route::get('/admin/users/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
Route::delete('/admin/users/{id}', [AdminController::class, 'delete'])->name('admin.user.delete');
Route::get('/admin/export-pdf', [AdminController::class, 'exportPdf'])->name('admin.export.pdf');
Route::post('/admin/confirm-code', function (\Illuminate\Http\Request $request) {
    if ($request->code === config('app.admin_code')) {
        Session::put('admin_confirmed', true);
        return back();
    } else {
        return back()->with('error', 'Code incorrect.');
    }
})->name('admin.confirm.code');
Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
Route::patch('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');


Route::post('/admin/logout-code', function () {
    Session::forget('admin_confirmed');
    return back();
})->name('admin.logout.code');

Route::middleware(['auth'])->group(function () {

    
    Route::get('/dashboard-client', [DeliveryController::class, 'clientDashboard'])->name('deliveries.client.dashboard');

 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::get('deliveries/import/csv', [DeliveryController::class, 'showImportForm'])->name('deliveries.import.form');
    Route::post('deliveries/import/csv', [DeliveryController::class, 'importCsv'])->name('deliveries.import.csv');
    Route::get('deliveries/optimize', [DeliveryController::class, 'optimizeRoute'])->name('deliveries.optimize');
    Route::get('deliveries/route/map', [DeliveryController::class, 'showRouteMap'])->name('deliveries.route.map');
    Route::get('deliveries/{id}/optimize', [DeliveryController::class, 'optimizeSingle'])->name('deliveries.optimize.single');
    Route::get('deliveries/{id}/export-pdf', [DeliveryController::class, 'exportSinglePdf'])->name('deliveries.export.pdf');
    

    Route::resource('deliveries', DeliveryController::class);
});



require __DIR__.'/auth.php';
