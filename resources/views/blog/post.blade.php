@extends('layouts.blog')

@section('title', 'Блог')

@section('content')
    <div class="row tm-row">
        <div class="col-lg-8 tm-post-col">
            <div class="tm-post-full">
                <div class="mb-4">
                    <h2 class="pt-2 tm-color-primary tm-post-title"> {{ $post->title }} </h2>
                    <p class="tm-mb-40">{{ $post->created_at }} Автор {{ $post->user->name }}</p>
                    <p>
                        {{$post->text}}
                    </p>
                </div>

                <!-- Comments -->
                <x-comments :comments="$post->comments" :postId="$post->id"/>
            </div>
        </div>
@endsection
