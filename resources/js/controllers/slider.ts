// todo этот слайдер умер, надо найти другой
class Slider {
    protected blaze = null;
    protected className = 'blaze-slider';
    protected inited = false;
    protected sliderOptions = {
        slider: {
            all: {
                enableAutoplay: true,
                stopAutoplayOnInteraction: true,
                autoplayInterval: 5000,
                transitionDuration: 1000,
                enablePagination: false,
            },
        },
        gallery: {
            all: {
                enablePagination: false,
                draggable: false,
            },
        },
        galleryPagination: {
            all: {
                slidesToShow: 4,
                enablePagination: true,
                draggable: false,
            },
            '(min-width: 639px)': {
                slidesToShow: 5,
            },
            '(min-width: 1023px)': {
                slidesToShow: 6,
            },
            '(min-width: 1279px)': {
                slidesToShow: 8,
            },
        },
    };

    public async init() {
        if (this.inited) return this;

        const sliders = document.getElementsByClassName(this.className) as HTMLCollectionOf<HTMLElement>;
        if (sliders.length) {
            await this.importBlaze();
            for (let i = 0; i < sliders.length; i++) {
                const elem = sliders[i];
                if (elem.dataset.options === 'slider') {
                    this.initSlider(elem);
                } else if (elem.dataset.options === 'gallery') {
                    this.initGallery(elem);
                }
            }
        }

        return this;
    }

    protected async importBlaze() {
        if (!this.blaze) {
            let BlazeSlider = await import('blaze-slider');
            this.blaze = BlazeSlider.default;
        }
        return this.blaze;
    }

    protected initGallery(item: HTMLElement) {
        const gallery = new this.blaze(item, this.sliderOptions.gallery);
        const paginationEl = item.querySelector('.blaze-slider-pagination');
        if (!paginationEl) return;
        const pagination = new this.blaze(paginationEl, this.sliderOptions.galleryPagination);

        pagination.onSlide(() => {
            this.syncSliders(pagination, gallery);
        });

        pagination.el.querySelectorAll('.blaze-track > *').forEach((el, i) => {
            el.addEventListener('click', () => {
                this.slideTo(pagination, i);
            });
        });
    }

    protected syncSliders(from, to) {
        this.slideTo(to, from.stateIndex);
    }

    protected slideTo(slider, toIndex) {
        const sliderIndex = slider.stateIndex;
        if (sliderIndex > toIndex) {
            slider.prev(sliderIndex - toIndex);
        } else if(toIndex > sliderIndex) {
            slider.next(toIndex - sliderIndex);
        }
    }

    protected initSlider(item: HTMLElement) {
        new this.blaze(item, this.sliderOptions.slider);
    }
}

export const slider = new Slider();
