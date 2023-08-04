@extends('layouts.blog')

@section('title', 'Блог')

@section('content')
    <div class="row tm-row">
        <div class="col-lg-8 tm-post-col">
            <div class="tm-post-full">
                <div class="mb-4">
                    <h2 class="pt-2 tm-color-primary tm-post-title">Single Post of Xtra Blog HTML Template</h2>
                    <p class="tm-mb-40">June 16, 2020 posted by Admin Nat</p>
                    <p>
                        This is a description of the video post. You can also have an image instead of
                        the video. You can free download
                        <a rel="nofollow" href="https://templatemo.com/tm-553-xtra-blog" target="_blank">Xtra Blog Template</a>
                        from TemplateMo website. Phasellus maximus quis est sit amet maximus. Vestibulum vel rutrum
                        lorem, ac sodales augue. Aliquam erat volutpat. Duis lectus orci, blandit in arcu
                        est, elementum tincidunt lectus. Praesent vel justo tempor, varius lacus a,
                        pharetra lacus. </p>
                    <p>
                        Duis pretium efficitur nunc. Mauris vehicula nibh nisi. Curabitur gravida neque
                        dignissim, aliquet nulla sed, condimentum nulla. Pellentesque id venenatis
                        quam, id cursus velit. Fusce semper tortor ac metus iaculis varius. Praesent
                        aliquam ex vel lectus ornare tristique. Nunc et eros quis enim feugiat tincidunt
                        et vitae dui.
                    </p>
                    <span class="d-block text-right tm-color-primary">Creative . Design . Business</span>
                </div>

                <!-- Comments -->
                <div>
                    <h2 class="tm-color-primary tm-post-title">Comments</h2>
                    <hr class="tm-hr-primary tm-mb-45">
                    <div class="tm-comment tm-mb-45">
                        <figure class="tm-comment-figure">
                            <img src="img/comment-1.jpg" alt="Image" class="mb-2 rounded-circle img-thumbnail">
                            <figcaption class="tm-color-primary text-center">Mark Sonny</figcaption>
                        </figure>
                        <div>
                            <p>
                                Praesent aliquam ex vel lectus ornare tritique. Nunc et eros
                                quis enim feugiat tincidunt et vitae dui. Nullam consectetur
                                justo ac ex laoreet rhoncus. Nunc id leo pretium, faucibus
                                sapien vel, euismod turpis.
                            </p>
                            <div class="d-flex justify-content-between">
                                <a href="#" class="tm-color-primary">REPLY</a>
                                <span class="tm-color-primary">June 14, 2020</span>
                            </div>
                        </div>
                    </div>
                    <div class="tm-comment-reply tm-mb-45">
                        <hr>
                        <div class="tm-comment">
                            <figure class="tm-comment-figure">
                                <img src="img/comment-2.jpg" alt="Image" class="mb-2 rounded-circle img-thumbnail">
                                <figcaption class="tm-color-primary text-center">Jewel Soft</figcaption>
                            </figure>
                            <p>
                                Nunc et eros quis enim feugiat tincidunt et vitae dui.
                                Nullam consectetur justo ac ex laoreet rhoncus. Nunc
                                id leo pretium, faucibus sapien vel, euismod turpis.
                            </p>
                        </div>
                        <span class="d-block text-right tm-color-primary">June 21, 2020</span>
                    </div>
                    <form action="" class="mb-5 tm-comment-form">
                        <h2 class="tm-color-primary tm-post-title mb-4">Your comment</h2>
                        <div class="mb-4">
                            <input class="form-control" name="name" type="text">
                        </div>
                        <div class="mb-4">
                            <input class="form-control" name="email" type="text">
                        </div>
                        <div class="mb-4">
                            <textarea class="form-control" name="message" rows="6"></textarea>
                        </div>
                        <div class="text-right">
                            <button class="tm-btn tm-btn-primary tm-btn-small">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
