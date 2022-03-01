@extends('layouts.app')

@section('title')
    Список категорий
@endsection

@section('content')
    <h1>
        Список категорий
    </h1>
    <table class="table table-bordered mb-5">
        <thead>
            <tr>
                <th>№</th>
                <th>Наименование</th>
                <th>Описание</th>
                <th>Наименований товаров</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $idr => $category)
            <tr>
                <td>{{ $idr + 1 }}</td>
                <td>{{$category->name}}</td>
                <td>{{$category->description}}</td>
                <td>{{$category->products()->count()}}</td>
            </tr>
            @empty
            <tr>
                <td class="text-center" colspan="3">Категорий пока нет</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <form method="post" action="{{ route('saveCategory') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-5">
                <label class="form-label">Новая категория</label>
                <input name="new_category" class="form-control">
        </div>
        <div class="mb-3">
                <label class="form-label">Описание новой категории</label>
                <input name="new_categorydescript" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Изображение для категории</label>
            <image class="picture mb-2" src="">
            <input type="file" name="picture" class="form-control">
        </div>
        <button name="savenew" value="1" type="submit" class="btn btn-primary mb-3">Сохранить</button>
        <div class="mb-3">
            <label class="form-label">Открыть файл для импорта категорий</label>
            
            <input type="file" name="fileimportcategories" class="form-control mb-3">
            <button name="ImportCategories" value="1" type="submit" class ="btn btn-primary mb-5">Импорт категорий</button>
        </div>
    </form>
@endsection