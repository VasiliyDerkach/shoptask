<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProductsImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $filename;
    /**
     * Create a new job instance.
     
     * @return void
     */
    public function __construct( $filename)
    {
        //dd($vfilename);
        $this->filename=$filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       // dd($this->filename);
        $file=fopen($this->filename,'r');
        //$file=fopen('imProducts.csv','r');
      
        $i=0;
        $insert=[];
        while ($row=fgetcsv($file,1000,';'))
        {
            if($i++==0)
            {
                $bom=pack('H*','EFBBBF');
                $row=preg_replace("/^$bom/",'',$row);
                $columns=$row;
                //dd($columns);
                continue;
            }
            //dd($row);
            $data=array_combine($columns,$row);
            $data['created_at']=date('Y-m-d H:i:s');
            $data['updated_at']=date('Y-m-d H:i:s');
            $data['name']=iconv('windows-1251','utf-8//IGNORE',$data['name']);
            $data['description']=iconv('windows-1251','utf-8//IGNORE',$data['description']);
            $idex=Product::find($data['id']);
            if ($idex)
            {
                $idex->update([
                    'name' => $data['name'],
                    'updated_at' => $data['updated_at'],
                    'price' => $data['price'],
                    'categoty_id' => $data['category_id'],
                    'description' => $data['description']
                 ]);
            }
            
            else{
                $olddata=Product::where('name',$data['name'])->where('price',$data['price'])->
                where('categoty_id',$data['categoty_id'])->
                where('description',$data['description'])->count();
                
                if ($olddata==0){
                    //dd($olddata);
                    $insert[]=$data;
                }
            } 
        }
        //dd($insert);
        Product::insert($insert);
        session()->flash('Импортировано {{$i}} продукта');
    }
}
