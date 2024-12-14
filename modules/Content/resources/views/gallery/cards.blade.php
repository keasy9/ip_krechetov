@use(Modules\Content\Enums\GalleryItemTypeEnum)
<section class="w-full bg-black text-white p-5 space-y-5 lg:grid lg:grid-cols-2 lg:gap-5 lg:space-y-0 xl:grid-cols-3">
    @foreach($gallery->items as $slide)
        <div
            @class([
                'bg-white' => $slide->media?->isEmpty(),
                'relative',
                'rounded-xl',
            ])
        >
            @if ($slide->media?->isNotEmpty())
                @if ($slide->type === GalleryItemTypeEnum::image)
                    {{ $slide->media->first()->img()->attributes(['class' => 'absolute top-0 left-0 rounded-xl']) }}

                @elseif($slide->type === GalleryItemTypeEnum::video)
                    <video
                        class="absolute top-0 left-0"
                        src="{{ $slide->media->first()->getUrl() }}"
                        autoplay
                        muted
                        loop
                        webkit-playsinline
                        playsinline
                        disablepicrureinpicture
                    >
                        <source
                            src="{{ $slide->media->first()->getUrl() }}"
                            type="{{ $slide->media->first()->mime_type }}"
                        >
                    </video>
                @endif
            @endif
            <div class="z-10 relative text-black bg-white bg-opacity-50 rounded-xl p-5 text-center h-full flex justify-between flex-col">
                <h5 class="font-bold text-red-500 mb-2 text-lg">{{ $slide->short_description }}</h5>
                <p class="font-medium">{!! $slide->description !!}</p>
            </div>
        </div>
    @endforeach
</section>
