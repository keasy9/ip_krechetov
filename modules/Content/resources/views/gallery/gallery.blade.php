@use(Modules\Content\Enums\GalleryItemTypeEnum)
<section class="blaze-slider w-full" data-options="gallery">
    <div class="blaze-container" aria-label="Slider">
        <h3>{{ $gallery->name }}</h3>
        <div class="blaze-track-container">
            <div class="blaze-track">
                @foreach($gallery->items as $slide)
                    @continue($slide->media?->isEmpty())
                    <div class="relative">
                        <div>
                            @if ($slide->type === GalleryItemTypeEnum::image)
                                {{ $slide->media->first() }}
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
                            @elseif($slide->type === GalleryItemTypeEnum::iframe)
                                {!! $slide->iframe !!}
                            @endif
                        </div>
                        <p>{!! $slide->description !!}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="blaze-pagination blaze-slider-pagination">
            <div class="blaze-container">
                <div class="blaze-track-container">
                    <div class="blaze-track">
                        @foreach($gallery->items as $slide)
                            @continue($slide->media?->isEmpty())
                            <div class="relative">
                                <div>
                                    @if ($slide->type === GalleryItemTypeEnum::image)
                                        {{ $slide->media->first()->img()->conversion('thumb') }}
                                    @else
                                        {{ $slide->getMedia('thumb') }}
                                    @endif
                                </div>
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
        </div>
    </div>
</section>
