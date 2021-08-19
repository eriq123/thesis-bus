<?php

namespace App\View\Components;

use Illuminate\View\Component;

class modal extends Component
{
    public $id;
    public $formID;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id = 'crud-modal', $formID = 'crudModalForm')
    {
        $this->id = $id;
        $this->formID = $formID;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
