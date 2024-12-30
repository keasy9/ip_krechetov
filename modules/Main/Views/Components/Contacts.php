<?php

namespace Modules\Main\Views\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Contacts extends Component
{
    public function __construct(protected array $data)
    {
    }

    public function render(): View|Closure|string
    {
        if (isset($this->data['schedule'])) {
            $schedule = [];
            $previousTime = '';
            $previousTimeDay = '';
            foreach ($this->data['schedule'] as $day => $time) {
                $schedule[$day] = [
                    'day'     => $day,
                    'time'    => $time,
                    'red'     => Str::lower($time) === 'выходной',
                    'rowspan' => 1,
                ];
                if ($time === $previousTime) {
                    $schedule[$previousTimeDay]['rowspan'] ??= 1;
                    $schedule[$previousTimeDay]['rowspan']++;
                    unset($schedule[$day]['time']);
                } else {
                    $previousTime = $time;
                    $previousTimeDay = $day;
                }
            }
            $this->data['schedule'] = $schedule;
        }

        return view('main::contacts', ['data' => $this->data]);
    }
}
