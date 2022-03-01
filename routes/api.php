<?php

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function () {
    $id = request('id');
    if (!$id) {
        return User::get();
    }
    
    return User::findOrFail($id);
});
Route::get('/categories', function () {
    $id = request('id');
    //if (!$id) {
        return Category::get();
    //}
    
    //return User::findOrFail($id);
});
Route::get('/orders', function () {
    $id = request('id');
    
    if (!$id) {
        return [];
    }
    
    return Order::where('user_id',$id)->get();
    //'products'=>
    //Order::where('user_id',$id)->products()->get()];
});    
Route::get('/orderproducts', function () {
    
    $id = request('orderid');
    
    if (!$id) {
        return [];
    }
    //session()->put('selectOrder',$id);
    
    return Order::findOrFail($id)->products()->get();
});   
Route::get('/saveMn', function ()   
{
    if (request('mainaddress') && request('userId'))
    {
        $idMaimAddress=request('mainaddress');
        dd( $idMaimAddress);
        $userId=request('userId');
           Address::where('user_id', $userId)->update([
                'main' => 0
             ]);
             
       Address::where('id', $idMaimAddress)->update([
            'main' => 1
        ]);
        return true;
    }
    else{
        return false;
    }

});

