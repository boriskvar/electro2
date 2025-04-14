<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProductShare extends Component
{
    public $productSocialLinks;

    public function __construct($productSocialLinks)
    {
        $this->productSocialLinks = $productSocialLinks;
    }

    public function render()
    {
        return view('components.product-share');
    }
}
