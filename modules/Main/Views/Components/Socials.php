<?php

namespace Modules\Main\Views\Components;

use App\Models\MenuItem;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Socials extends Component
{
    public function __construct(protected array $data)
    {
    }

    public function render(): View|Closure|string
    {
        if (isset($this->data['menu'])) {
            $this->data['menu'] = MenuItem::query()->whereMenuType($this->data['menu'])->get();
        }

        return view('main::components.socials', ['data' => $this->data]);
    }
}
