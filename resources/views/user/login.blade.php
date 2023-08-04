@extends('layouts.site')

@section('title', 'Авторизация')

@section('content')
    <h1>Авторизация</h1>
    @foreach($errors->all() as $error)
        <div>
            {{ $error }}
        </div>
    @endforeach
    <form method="post" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Адрес почты"
                   required maxlength="255" value="{{ old('email') ?? '' }}">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="password" placeholder="Ваш пароль"
                   required maxlength="255" value="">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info text-white">Войти</button>
        </div>
    </form>
@endsection
