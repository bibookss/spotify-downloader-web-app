<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AlbumCard extends Component
{
    /**
     * Create a new component instance.
     */
    public $album;
    
    public function __construct($album)
    {
        $this->album = $album;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.album-card');
    }
}
