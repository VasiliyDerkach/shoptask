<?php

namespace App\Http\Controllers;

use App\Jobs\CategoriesImport;
use App\Jobs\ExportProducts;
use App\Jobs\ProductsExport;
use App\Jobs\ProductsImport;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    public function admin ()
    {   
        
        
        return view('admin.admin');
    }

    public function users ()
    {

        $users = User::paginate(3);
        $roles = Role::get();

        $data = [
            'title' => 'Список пользователей',
            'users' => $users,
            'roles' => $roles,
        ];
        return view('admin.users', $data);
    }

    public function products ()
    {
        $categories = Category::get();
        //$input = request()->all();
        
        $categoryId=request('categoryId');
        $data = [
            'title' => 'Список категорий',
            'categories' => $categories,
            'categoryId' => $categoryId??'',
            'productlist'=>'',
            'categoryName'=>''
        ];  
        
        return view('admin.products',$data);
    }

    public function categories ()
    {
        $categories = Category::get();

        $data = [
            'title' => 'Список категорий',
            'categories' => $categories
        ];            
        return view('admin.categories', $data);
    }

    public function enterAsUser ($id)
    {
        Auth::loginUsingId($id);
        return redirect()->route('adminUsers');
    }

    public function addRole ()
    {
        request()->validate([
            'name' => 'required|min:3',
        ]);

        Role::create([
            'name' => request('name')
        ]);
        return back();
    }

    public function addRoleToUser ()
    {
        request()->validate([
            'user_id' => 'required',
            'role_id' => 'required',
        ]);

        $user = User::find(request('user_id'));
        $user->roles()->attach(Role::find(request('role_id')));
        return back();
    }
    
    public function listCategory (Request $request)
    {
        
        $flag=Request()->all();
        //dd($flag);
        $categoryId=request('categoryId');
        $categories = Category::get();
        if ($categoryId)
        {
            
            $categoryName=Category::find($categoryId)->name;
            
            request()->session()->put('categoryId',$categoryId);
        }
        else{
            
            $categoryName='';
            $categoryId='';
            session()->flash('Вы не выбрали категорию');
        }
    
        if (request('ExportProducts')) {
            
            ProductsExport::dispatch();
        }
        elseif (request('ImportProducts'))
        {
           
            
            $fileimportproducts =request('fileimportproducts');
            
            if($fileimportproducts)
                {
                    if(file_exists('import/fileimportproducts.csv')){
                        $fileimportproducts->delete('import','fileimportproducts.csv');
                    }
                    $fileimportproducts->storeAs('import','fileimportproducts.csv');
                    ProductsImport::dispatch('C:/laravel/laravel3/storage/app/import/fileimportproducts.csv');
                }
                else 
                {
                    session()->flash('Не выбран файл для импорта');
                }
        }
                  
 
        elseif (request('NewProduct')) {
            
            $input = $flag;
            request()->validate([
                'categoryId' =>  'required',
                'new_product' => 'required',
                //'price' => 'required',
                'picture' => 'mimetypes:image/*'
                //, 'new_productdescript' => 'required'
            ]);
            $categoryId=$input['categoryId'];
            $picture = $input['picture'] ?? '';
            $newProduct = $input['new_product'];
            $newProductdescript=$input['new_productdescript'];
            $price=$input['price'];
           
    
            if ($newProduct ) {
                $newProductexis=Product::where('name', $newProduct)->count();
              
                if ($newProductexis==0)
                {
                  
                    if ($picture) {
                        $ext = $picture->getClientOriginalExtension();
                        $fileName = time() . rand(10000, 99999) . '.' . $ext;
                        $picture->storeAs('public/product', $fileName);
                        
                    }
                    Product::create([
                        'category_id' =>$categoryId,
                        'name' => $newProduct,
                        'description' => $newProductdescript,
                        'picture' => "product/$fileName",
                        'price'=>$price
                    ]);
                    session()->flash('ProductSaved');
                }
                else{
                    session()->flash('ProductExist');
                }
            }
        }
        if ($categoryId)
            {
                
                $productlist=Product::where('category_id',$categoryId)->orderby('created_at')->get();
               
            }
            else{
                $productlist='';
            }
        
        return view('admin.products', compact('productlist', 'categoryName', 'categoryId','categories'));

    }
    public function saveCategory (Request $request)
    {
        $input = request()->all();
        if (request('savenew'))
        {
            request()->validate([
                'new_category' => 'required',
                'picture' => 'mimetypes:image/*'
                //, 'new_categorydescript' => 'required'
            ]);
            $picture = $input['picture'] ?? null;
            $newCategory = $input['new_category'];
            $newCategorydescript=$input['new_categorydescript'];
            //dd($newCategorydescript);
           
    
            if ($newCategory ) {
                $newCategoryexis=Category::where('name', $newCategory)->get();
               
                if (!$newCategoryexis)
                {
                    if ($picture) {
                        $ext = $picture->getClientOriginalExtension();
                        $fileName = time() . rand(10000, 99999) . '.' . $ext;
                        $picture->storeAs('public/category', $fileName);
                        
                    }
                    Category::create([
                        'name' => $newCategory,
                        'description' => $newCategorydescript,
                        'picture' => "category/$fileName"
                    ]);
                    session()->flash('CategorySaved');
                }
                else{
                    session()->flash('CategoryExist');
                }
            }
    
        }
        elseif (request('ImportCategories'))
        {
            $fileimportcategories =request('fileimportcategories');
            
            if($fileimportcategories)
                {
                    if (file_exists('import/fileimportcategories.csv')) {
                        $fileimportcategories->delete('import','fileimportcategories.csv');
                    }
                    $fileimportcategories->storeAs('import','fileimportcategories.csv');
                    
                    CategoriesImport::dispatch('C:/laravel/laravel3/storage/app/import/fileimportcategories.csv');
                }
                else 
                {
                    session()->flash('Не выбран файл для импорта');
                }

        }

        
        
        return back();
    }
    public function saveProduct (Request $request)
    {
        $input = request()->all();
        request()->validate([
            'categoryId' =>  'required',
            'new_product' => 'required',
            'picture' => 'mimetypes:image/*'
            //, 'new_productdescript' => 'required'
        ]);
        $categoryId=$input['categoryId'];
        $picture = $input['picture'] ?? null;
        $newProduct = $input['new_product'];
        $newProductdescript=$input['new_productdescript'];
      
       

        if ($newProduct ) {
            $newProductexis=Product::where('name', $newProduct);
           
            if (!$newProductexis)
            {
                if ($picture) {
                    $ext = $picture->getClientOriginalExtension();
                    $fileName = time() . rand(10000, 99999) . '.' . $ext;
                    $picture->storeAs('public/product', $fileName);
                    
                }
                Product::create([
                    'category_id' =>$categoryId,
                    'name' => $newProduct,
                    'description' => $newProductdescript,
                    'picture' => "product/$fileName"
                ]);
                session()->flash('ProductSaved');
            }
            else{
                session()->flash('ProductExist');
            }
        }

        
        
        return back();
    }

}
