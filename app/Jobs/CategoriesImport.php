<?php

namespace App\Jobs;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CategoriesImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $filename;
    /**
     * Create a new job instance.
     *
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
        //dd($this->filename);
        $file=fopen($this->filename,'r');
       
      
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
            //dd($data);
            $data['created_at']=date('Y-m-d H:i:s');
            $data['updated_at']=date('Y-m-d H:i:s');
            $idex=Category::find($data['id']);
            $data['name']=iconv('windows-1251','utf-8//IGNORE',$data['name']);
            $data['description']=iconv('windows-1251','utf-8//IGNORE',$data['description']);
            //dd($data);
            if ($idex)
            {
                
                //dd($data);
                $idex->update([
                    'name' => $data['name'],
                    'updated_at' => $data['updated_at'],
                    'description' => $data['description']
                 ]);
            }
            
            else{
                //dd($data);
                $olddata=Category::where('name',$data['name'])->
                where('description',$data['description'])->count();
                
                if ($olddata==0){
                    //dd($olddata);
                    $insert[]=$data;
                }
            } 
        }
        //dd($insert);
        Category::insert($insert);
        session()->flash('Импортировано {{$i}} продукта');
    }
}
