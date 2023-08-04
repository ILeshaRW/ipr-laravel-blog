@extends('layouts.blog')

@section('title', 'Блог')

@section('content')
    <div class="row tm-row">
        @foreach($posts as $post)
            <article class="col-12 col-md-6 tm-post">
                <hr class="tm-hr-primary">
                <a href="{{ route('post', ['postId' => $post->id]) }}" class="effect-lily tm-post-link tm-pt-60">
                    <h2 class="tm-pt-30 tm-color-primary tm-post-title">{{ $post->title }}</h2>
                </a>
                <p class="tm-pt-30">
                    {{ $post->preview_text }}
                </p>
                <div class="d-flex justify-content-between tm-pt-45">
                    <span class="tm-color-primary">{{ $post->created_at }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span>Комментарии: {{ $post->comments->count()  }}</span>
                    <span>{{ $post->user->name }}</span>
                </div>
            </article>
        @endforeach
    </div>
    {{ $posts->links('vendor.pagination.default') }}
@endsection
