<section class="py-3 px-5 bg-yellow-100">
    @if(!empty($data['title']))
        <h3 class="text-2xl font-bold">{!! $data['title'] !!}</h3>
    @endif
    @if(!empty($data['subtitle']))
        <h4 class="ml-4">{!! $data['subtitle'] !!}</h4>
    @endif
    @if(!empty($data['menu']) && $data['menu']->isNotEmpty())
        <div class="flex justify-end gap-4">
            @foreach($data['menu'] as $menuItem)
                <x-dynamic-component :component="$menuItem->title" class="h-10 cursor-pointer hover:text-red-400" />
            @endforeach
        </div>
    @endif
</section>
