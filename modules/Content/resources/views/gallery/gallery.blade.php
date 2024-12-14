@use(Modules\Content\Enums\GalleryItemTypeEnum)
@use(App\Enums\MediaCollectionEnum)
<section class="blaze-slider w-full px-5 bg-black" data-options="gallery">
    <div class="blaze-container" aria-label="Slider">
        <h3 class="text-red-500 font-bold text-2xl mb-2">{{ $gallery->name }}</h3>
        <div class="blaze-track-container pb-2">
            <div class="blaze-track">
                @foreach($gallery->items as $slide)
                    @continue($slide->media?->isEmpty())
                    <div class="flex flex-col gap-2 lg:flex-row lg:gap-5">
                        <div class="flex-grow lg:max-w-xl xl:max-w-none lg:w-1/2 lg:max-h-[30rem]">
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
                        @if (!empty($slide->description))
                            <p class="font-bold text-white lg:w-1/2">{!! $slide->description !!}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="blaze-pagination blaze-slider-pagination">
            <div class="blaze-container">
                <button class="blaze-prev" aria-label="Перейти на предыдущий слайд">
                    <x-ionicon-chevron-back-sharp class="w-10 h-10 text-red-500"/>
                </button>
                <div class="blaze-track-container max-w-[65vw] mx-auto mt-1 sm:max-w-[80vw] lg:max-w-[85vw] xl:max-w-[90vw]">
                    <div class="blaze-track">
                        @foreach($gallery->items as $slide)
                            @continue($slide->media?->isEmpty())
                            <div @class(['blaze-slide-current' => $loop->first])>
                                @if ($slide->type === GalleryItemTypeEnum::image)
                                    {{ $slide->media->first()->img()->conversion('thumb') }}
                                @else
                                    {{ $slide->getMedia(MediaCollectionEnum::galleryItemThumb->value)->first() }}
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <button class="blaze-next" aria-label="Перейти на следующий слайд">
                    <x-ionicon-chevron-forward-sharp class="w-10 h-10 text-red-500"/>
                </button>
            </div>
        </div>
    </div>
</section>
