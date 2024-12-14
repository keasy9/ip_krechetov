@use(Modules\Content\Enums\GalleryItemTypeEnum)
<section class="blaze-slider w-full h-screen">
    <div class="blaze-container" aria-label="Slider">
        <div class="blaze-track-container">
            <div class="blaze-track">
                @foreach($gallery->items as $slide)
                    @continue($slide->media?->isEmpty())
                    <div class="relative">
                        @if ($slide->type === GalleryItemTypeEnum::image)
                            {{ $slide->media->first() }}
                            <div
                                class="absolute bottom-0 p-6 w-full bg-opacity-50 bg-black text-white font-bold md:max-w-xl rounded-r-2xl md:bottom-10 lg:left-20 lg:rounded-l-2xl"
                            >
                                {!! $slide->description !!}
                            </div>
                        @elseif($slide->type === GalleryItemTypeEnum::video)
                            <video
                                src="{{ $slide->media->first()->getUrl() }}"
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
                            <div
                                class="absolute bottom-0 p-6 w-full bg-opacity-50 bg-black text-white font-bold md:max-w-xl rounded-r-2xl md:bottom-10 lg:left-20 lg:rounded-l-2xl"
                            >
                                {!! $slide->description !!}
                            </div>
                        @elseif($slide->type === GalleryItemTypeEnum::iframe)
                            {!! $slide->iframe !!}
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <button class="blaze-prev" aria-label="Go to previous slide">
            <x-ionicon-chevron-back-sharp class="w-12 h-12 text-red-500"/>
        </button>
        <button class="blaze-next" aria-label="Go to next slide">
            <x-ionicon-chevron-forward-sharp class="w-12 h-12 text-red-500"/>
        </button>
    </div>
</section>
