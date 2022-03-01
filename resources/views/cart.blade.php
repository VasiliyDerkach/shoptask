@extends('layouts.app')

@section('styles')
<style>
    .product-buttons {
        display: flex;
        justify-content: space-evenly;
        line-height: 37px;
    }
</style>
@endsection

@section('content')
    <order-component :id-User="{{$userId}}" :cart="{{$cartlst}}" :prods="{{$products}}"
    :user="{{$user}}" address="{{$address}}">

    </order-component>
    
@endsection