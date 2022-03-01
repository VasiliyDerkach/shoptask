@extends('layouts.app')

@section('title')
    Админка
@endsection

@section('content')
<br>
    <a href="{{ route('adminUsers')}}">Список пользователей</a>
    <br>
    <a href="{{ route('adminProducts')}}">Список продуктов</a>
    <br>
    <a href="{{ route('adminCategories')}}">Список категорий</a>
@endsection