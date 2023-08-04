@extends('layouts.site')

@section('title', 'Регистрация')

@section('content')
    <h1>Регистрация</h1>
    <form method="post" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control" name="name" placeholder="Имя"
                   required maxlength="100" value="{{ old('name') ?? '' }}">
            @foreach($errors->get('name') as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="last_name" placeholder="Фамилия"
                   required maxlength="100" value="{{ old('last_name') ?? '' }}">
            @foreach($errors->get('last_name') as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Адрес почты"
                   required maxlength="255" value="{{ old('email') ?? '' }}">
            @foreach($errors->get('email') as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="password" placeholder="Придумайте пароль"
                   required maxlength="255" value="">
            @foreach($errors->get('password') as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="password_confirmation"
                   placeholder="Пароль еще раз" required maxlength="255" value="">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info text-white">Регистрация</button>
        </div>
    </form>
@endsection
