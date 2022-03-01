<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\OrderCreated;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{

    public function cart () {
        $cart = session('cart') ?? []; 
        $selectOrder=session()->get('selectOrder')??'';
        
        $cartlst=collect($cart);
        $products = Product::whereIn('id', array_keys($cart))
                        ->get()
                        ->transform(function ($product) use ($cart) {
                            $product->quantity = $cart[$product->id];
                            return $product;
                        });
       
        $user = Auth::user();
        
        $userId=$user?$user->id:'';
        $address = $user ? $user->addresses()->where('main', 1)->first()->address ?? '' : '';
        //dd('$selectOrder=',$selectOrder);
        return view('cart', compact('products', 'user', 'address','cartlst','userId','selectOrder'));
    }

    public function removeFromCart () {
        $productId = request('id');
        $cart = session('cart') ?? [];
        $inBlade= request('inBlade');
    
        if (!isset($cart[$productId])){
            if(isset($inBlade)) {
                return back();
            }
    
            return true;
        }
        $quantity = $cart[$productId];
        if ($quantity > 1) {
            $cart[$productId] = --$quantity;
            session()->put('cart', $cart);
            if(isset($inBlade)) {
                return back();
            }
    
            return $cart[$productId];
        } else {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            if(isset($inBlade)) {
                return back();
            }
    
            return null;
        }

        
    }
    
    public function addToCart ()
    {
        $productId = request('id');
        
        $cart = session('cart') ?? [];

        if (isset($cart[$productId])) {
            $cart[$productId] = ++$cart[$productId];
        } else {
            $cart[$productId] = 1;
        }

        session()->put('cart', $cart);
        $inBlade= request('inBlade');
        if(isset($inBlade)) {
            return back();
        }
        return $cart[$productId];
    }

    public function createOrder ()
    {
            request()->validate([
                'name' => 'required',
                'email' => 'required|email',
                'address' => 'required',
                'register_confirmation' => 'accepted|sometimes'
            ]);
    
                DB::transaction(function () {
                    
                    $user = Auth::user();
                    if (!$user) {
                        $password = \Illuminate\Support\Str::random(8);
                        $user = User::create([
                            'name' => request('name'),
                            'email' => request('email'),
                            'password' => Hash::make($password)
                        ]);
            
                        $address = Address::create([
                            'user_id' => $user->id,
                            'address' => request('address'),
                            'main' => 1
                        ]);
    
                        Auth::loginUsingId($user->id);
                    }
            
                    $address = $user->getMainAddress();
            
                    $cart = session('cart');
                    $order = Order::create([
                        'user_id' => $user->id,
                        'address_id' => $address->id
                    ]);
            
                    foreach ($cart as $id => $quantity) {
                        $product = Product::find($id);
                        $order->products()->attach($product, [
                            'quantity' => $quantity,
                            'price' => $product->price
                        ]);
                    }
            
                    $data = [
                        'products' => $order->products,
                        'name' => $user->name
                        , 'password' => $password??''
                    ];
                    //Mail::to($user->email)->send(new OrderCreated($data));
                });        
    
                session()->forget('cart');
                return true;
        
      
            
    }
    public function addOrderToCart() {
    
        $id = request('orderid');
        
        if ($id) {
            $orderproducts=Order::findOrFail($id)->products()->get();
            $cart = session('cart') ?? [];
            session()->put('selectOrder',$id);
            $addcart=[];
             $flag=0;
            foreach($orderproducts as $orderproduct)
            {
                    $indz=(int)$orderproduct->id;
                    
                    if (!isset($cart[$indz]))
                    {
                        $quan=(int)$orderproduct->pivot->quantity;
                        $addcart[$indz]['quantity']=$quan;
                        $addcart[$indz]['name']=$orderproduct->name;
                        $addcart[$indz]['price']=$orderproduct->price;

                        $cart[$indz]=$quan;
                        $flag+=1;
                    }
            }
            session()->put('cart',$cart);
            
            
                return $addcart;
               
        }
        else {
            session()->flash('Не выбран заказ');
            return false;
            //redirect()->route('cart');
        }
     
    }
}
