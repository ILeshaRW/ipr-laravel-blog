<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Comments extends Component
{
    /**
     * Create a new component instance.
     * @param Collection|null $comments
     * @param int $postId
     */
    public function __construct(
        public ?Collection $comments,
        public int $postId
    ){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.comments');
    }
}
