@extends('layouts.app')

@section('title')
    Список продуктов
@endsection

@section('content')
<div>
    <h1>
        Список продуктов
    </h1>
    
        <example-component>
        </example-component>
</div>    
<div mb-5>    
    <h2>
        Выбрать категорию
    </h2>
    <form name='listCategory' method="post" action="{{ route('listCategory') }}" enctype="multipart/form-data">
        @csrf
        @if  (session('categoryId'))
            @php
            $categoryId=session('categoryId');
            @endphp
        @else
            @php
            $categoryId=-1;
            @endphp
        @endif
        <br>
        <ExampleComponent>
            
        </ExampleComponent>
        @if (isset($categories))
            @forelse ($categories as $idx => $category)
                
                    <label for="categoryId{{$category->id}}">{{$category->id}}.{{$category->name}}</label>
                    <input @if ($category->id==$categoryId) checked @endif class="dropdown-item" id="categoryId{{$category->id}}" type="radio" name='categoryId' value="{{$category->id}}" >
                
            @empty
                <a>Список категорий пуст</a>

            @endforelse
        @endif
        @if ($categoryName)
            <h1 class="mb-5">Выбрано {{$categoryName}}({{$productlist->count()}})</h1><br>
        @endif
        <button name="ListProducts" value="1" type="submit" class="btn btn-primary mb-5">Показать продукты для категории</button>
        <button name="NewProduct" value="1" type="submit" class="btn btn-primary mb-5">Добавить новый продукт</button>
        
        <button name="ExportProducts" value="1" type="submit" class ="btn btn-primary mb-5">Экспорт проудктов</button>
        <div class="mb-3">
            <label class="form-label">Открыть файл для импорта продуктов</label>
            
            <input type="file" name="fileimportproducts" class="form-control mb-3">
            <button name="ImportProducts" value="1" type="submit" class ="btn btn-primary mb-5">Импорт проудктов</button>
        </div>
        <div class="mb-5">
                <label class="form-label">Новый продукт</label>
                <input name="new_product" class="form-control">
        </div>
        <div class="mb-3">
                <label class="form-label">Описание нового продукта</label>
                <input name="new_productdescript" class="form-control">
        </div>
        <div class="mb-3">
                <label class="form-label">Цена нового продукта</label>
                <input name="price" class="form-control">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Изображение для нового продукта</label>
            <image class="picture mb-2" src="">
            <input type="file" name="picture" class="form-control">
        </div>
    </form>
            
        @if ($categoryName)
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Наименование</th>
                        <th>Описание</th>
                        <th>Цена</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @forelse ($productlist as $idr => $productlst)
                    <tr>
                        <td>{{ $idr + 1 }}</td>
                        <td>{{$productlst->name}}</td>
                        <td>{{$productlst->description}}</td>
                        <td>{{$productlst->price}}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="3">В этой категорий пока нет товаров</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <a> Категория не выбрана</a>         
        @endif
    </form>
  
    
</div>

@endsection