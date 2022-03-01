<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProductsExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //dd('dispatch');
        $products=Product::get()->toArray();
        $file=fopen('exportProducts.csv','w');
        $columns = [
            'id','name','descrption','picture','price','category_id',
            'created_ad','updated_at'
        ];
        fputcsv($file,$columns,';');

        foreach ($products as $ind=> $product)
        {
            //$product['id']=iconv('utf-8','windows-1251//IGNORE',$product['id']);
            $product['name']=iconv('utf-8','windows-1251//IGNORE',$product['name']);
            //$product['category_id']=iconv('utf-8','windows-1251//IGNORE',$product['category_id']);
            $product['description']=iconv('utf-8','windows-1251//IGNORE',$product['description']);
            $product['picture']=iconv('utf-8','windows-1251//IGNORE',$product['picture']);
            //$product['created_at']=iconv('utf-8','windows-1251//IGNORE',$product['created_at']);
            //$product['updated_at']=iconv('utf-8','windows-1251//IGNORE',$product['updated_at']);
            fputcsv($file,$product,';');
        }
        fclose($file);
        session()->flash('Экспортировано {{$ind}} продуктов');
    }
}
