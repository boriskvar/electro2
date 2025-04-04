<?php 

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
/**
* Хлебные крошки.
*
* @var array
*/
public $breadcrumbs;

/**
* Create a new component instance.
*
* @param array $breadcrumbs
* @return void
*/
public function __construct($breadcrumbs = [])
{
$this->breadcrumbs = $breadcrumbs;
}

/**
* Get the view / contents that represent the component.
*
* @return \Illuminate\View\View|string
*/
public function render()
{
return view('components.breadcrumbs');
}
}