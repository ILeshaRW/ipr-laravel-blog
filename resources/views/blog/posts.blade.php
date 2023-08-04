@extends('layouts.blog')

@section('title', 'Блог')

@section('content')
    <div class="row tm-row">
        <article class="col-12 col-md-6 tm-post">
            <hr class="tm-hr-primary">
            <a href="{{ route('post') }}" class="effect-lily tm-post-link tm-pt-60">
                <h2 class="tm-pt-30 tm-color-primary tm-post-title">Заголовок</h2>
            </a>
            <p class="tm-pt-30">
                Предварительное описание
            </p>
            <div class="d-flex justify-content-between tm-pt-45">
                <span class="tm-color-primary">дата</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <span>кол-во комментариев</span>
                <span>Автор</span>
            </div>
        </article>
    </div>
@endsection
