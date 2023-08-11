@extends('layouts.blog')

@section('title', 'Создать пост')

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="mb-4">
                <span class="text-danger">{{ $error }}</span>
            </div>
        @endforeach
    @endif
    <form method="POST" action="{{ route('create_post') }}">
        <div class="row tm-row">
            <div class="text-center text-info col-12">Название</div>
            <input class="col-12" type="text" name="title">
            <div class="text-center text-info col-12"> Превью </div>
            <textarea class="col-12" name="preview_text"></textarea>
            <div class="text-center text-info col-12"> Детальный текст </div>
            <textarea class="col-12" name="text"></textarea>
            @method('PUT')
            @csrf
            <button class="col-12 btn-primary" type="submit"> Создать </button>
        </div>
    </form>
@endsection
