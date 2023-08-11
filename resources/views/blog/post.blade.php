@extends('layouts.blog')

@section('title', 'Блог')

@section('content')
    <div class="row tm-row">
        <div class="col-lg-8 tm-post-col">
            <div class="tm-post-full">
                <div class="mb-4">
                    <h2 class="pt-2 tm-color-primary tm-post-title"> {{ $post->title }} </h2>
                    @can('update', $post)
                        <a href="{{ route('edit_post', ['post' => $post->id]) }}">
                            <i title="Редактировать" class="fa fa-edit"></i>
                        </a>
                    @endcan
                    <p class="tm-mb-40">{{ $post->created_at }} Автор {{ $post->user->name }}</p>
                    <p>
                        {{$post->text}}
                    </p>
                </div>

                <!-- Comments -->
                <x-comments :post="$post"/>
            </div>
        </div>
@endsection
