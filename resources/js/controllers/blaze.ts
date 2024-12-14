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
                enablePagination: true,
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
        new this.blaze(item, this.sliderOptions.gallery);

    }

    protected initSlider(item: HTMLElement) {
        new this.blaze(item, this.sliderOptions.slider);
    }
}

export const slider = new Slider();
