@use(Modules\Content\Enums\GalleryItemTypeEnum)

<section class="splide" aria-label="Splide Basic HTML Example">
    <div class="splide__track">
        <ul class="splide__list">
            @foreach($gallery->items as $slide)
                <li class="splide__slide">
                    @if ($slide->type === GalleryItemTypeEnum::image)
                        {{ $slide->media->first()->img()->attributes(['class' => 'w-full h-full object-cover']) }}
                    @elseif($slide->type === GalleryItemTypeEnum::video)
                        <video
                            src="{{ $slide->media->first()->getUrl() }}"
                            class="w-full h-full object-cover"
                            style="height: 100%;object-fit: cover;" {{-- todo почему не работает h-full? --}}
                            @if($slide->inline_video)
                                autoplay
                                muted
                                loop
                                webkit-playsinline
                                playsinline
                                disablepicrureinpicture
                            @else
                                controls
                            @endif
                        >
                            <source
                                src="{{ $slide->media->first()->getUrl() }}"
                                type="{{ $slide->media->first()->mime_type }}"
                            >
                        </video>
                    @elseif($slide->type === GalleryItemTypeEnum::iframe)
                        {!! $slide->iframe !!}
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</section>
