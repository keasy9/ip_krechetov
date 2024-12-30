<section class="py-3 bg-bg lg:flex">
    @if(!empty($data['title']))
        <h3 class="text-white font-bold pl-1 text-xl lg:hidden">{{ $data['title'] }}</h3>
    @endif
    @if(!empty($data['2gisId']))
        <div class="h-[30rem] lg:w-1/2 border-red-500 border-2">
            <script charset="utf-8" src="https://widgets.2gis.com/js/DGWidgetLoader.js"></script>
            <script charset="utf-8">new DGWidgetLoader({
                "pos": {"lat": 53.30686491109112, "lon": 83.61293077468873, "zoom": 15},
                "opt": {"city": "barnaul"},
                "org": [{"id": "{{ $data['2gisId'] }}"}]
            });</script>
        </div>
    @endif
    <div class="text-white p-5 sm:flex justify-between lg:flex-col-reverse lg:py-3">
        @if(!empty($data['schedule']))
            <table class="text-white font-bold border-spacing-y-1 border-separate">
                @foreach($data['schedule'] as $day => $schedule)
                    @if($loop->last)
                        <tr class="h-3"></tr>
                    @endif
                    <tr>
                        <td class="text-right pr-3">{{ $day }}</td>
                        @isset($schedule['time'])
                            <td
                                @class([
                                    'text-left',
                                    'pl-3',
                                    'border-l-2',
                                    'border-white'                  => Str::lower($schedule['time'] ?? '') !== 'выходной',
                                    'border-red-500'                => Str::lower($schedule['time'] ?? '') === 'выходной',
                                    "text-{$schedule['rowspan']}xl" => $schedule['rowspan'] > 1,
                                ])
                                @if($schedule['rowspan'] > 1)
                                    rowspan="{{ $schedule['rowspan'] }}"
                                @endif
                            >{{ $schedule['time'] }}</td>
                        @endisset
                    </tr>
                @endforeach
            </table>
        @endif
        <div class="flex justify-between mt-5 sm:flex-col-reverse sm:mt-0">
            @if(!empty($data['phone']))
                <div class="lg:mt-2">
                    @foreach($data['phone'] as $phone)
                        <a href="tel:{{ $phone }}" class="block text-red-500 font-bold underline hover:decoration-solid transition-all decoration-dashed">{{ $phone }}</a>
                    @endforeach
                </div>
            @endif
            @if(!empty($data['address']))
                <p>{!! nl2br($data['address']) !!}</p>
            @endif
        </div>
        @if(!empty($data['title']))
            <h3 class="hidden text-white font-bold pl-1 text-xl lg:block">{{ $data['title'] }}</h3>
        @endif
    </div>
</section>
