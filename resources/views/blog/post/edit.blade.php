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
    <form method="POST" action="{{ route('edit_post', ['post' => $post->id]) }}">
        <div class="row tm-row">
            <div class="text-center text-info col-12">Название</div>
            <input class="col-12" type="text" name="title" value="{{ $post->title }}">
            <div class="text-center text-info col-12"> Превью </div>
            <textarea class="col-12" name="preview_text">{{ $post->preview_text }}</textarea>
            <div class="text-center text-info col-12"> Детальный текст </div>
            <textarea class="col-12" name="text">{{ $post->text }}</textarea>
            @csrf
            <button class="col-12 btn-primary" type="submit"> Редактировать </button>
        </div>
    </form>
    @can('delete', $post)
        <form method="POST" action="{{ route('delete_post', ['post' => $post->id]) }}">
            @csrf
            @method('DELETE')
            <button class="btn-danger col-12" type="submit">Удалить пост</button>
        </form>
    @endcan
@endsection
