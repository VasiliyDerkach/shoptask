<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileJSController extends Controller
{
    public function profileJS (User $user)
    {        
        if (!Auth::user()) 
            return redirect()->route('home');
        if (Auth::user()->isAdmin() || $user->id == Auth::user()->id)
            return view('profilejs', compact('user'));

        return redirect()->route('home');
    }
    public function SaveIdMainAddress()
    {
        $d=request()->all();
        //dd($d);
        if ( request('mainaddress') && request('userId') )
        {
            $idMaimAddress=request('mainaddress');
            //dd( $idMaimAddress);
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

    }
    
    
    public function saveJS (Request $request)
    {
        $input = request()->all();
        //dd($input);
        $name = $input['name'];
        $email = $input['email'];
        $userId = $input['userId'];
        $picture = $input['picture'] ?? null;
        $newAddress = $input['new_address'];
      
        $mainCheck=$input['main_check'];
        $addressexist=false;
        $profileSaved=false;
        $user =$input['user'];

        request()->validate([
            'name' => 'required',
            'email' => "email|required|unique:users,email,{$user->id}",
            'picture' => 'mimetypes:image/*',
            'current_password' => 'current_password|required_with:password|nullable',
            'password' => 'confirmed|min:8|nullable'
        ]);

        if ($input['password']) {
            $user->password = Hash::make($input['password']);
            $user->save();
    
        }
        
      
        
        if ($mainCheck){
            $idMaimAddress=request('mainaddress');
               Address::where('user_id', $user->id)->update([
                    'main' => 0
                 ]);
                 
           Address::where('id', $idMaimAddress)->update([
                'main' => 1
            ]);
    
        }
        if ($newAddress ) {
            $addressexist=Address::where('user_id', $userId)->where('address',$newAddress )->first();
            
            if (!$addressexist)
            {
                if ($mainCheck==1){
                    Address::where('user_id', $user->id)->update([
                        'main' => 0
                    ]);
                }

                Address::create([
                    'user_id' => $user->id,
                    'address' => $newAddress,
                    'main' => $mainCheck
                ]);
            }
            else{
                $addressexist=true;
                //session()->flash('addressExist');
            }
        }

        if ($picture) {
            $ext = $picture->getClientOriginalExtension();
            $fileName = time() . rand(10000, 99999) . '.' . $ext;
            $picture->storeAs('public/users', $fileName);
            $user->picture = "users/$fileName";
        }

        $user->name = $name;
        $user->email = $email;
        $user->save();
        //session()->flash('profileSaved');
        $profileSaved=true;
        
        return compact('addressexist','profileSaved');
    }
}
