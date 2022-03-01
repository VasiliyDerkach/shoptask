@extends('layouts.app')

@section('title')
    Профиль
@endsection

@section('styles')
<style>
    .user-picture {
        width: 100px;
        border-radius: 100px;
        display: block;
    }

    .main-address {
        font-weight: bold;
    }
</style>
@endsection

@section('content')

    <profile-component :idUser="{{$user->id}}" :user="{{$user}}" :useraddress="{{$user->addresses}}">
    </profile-component>

@endsection