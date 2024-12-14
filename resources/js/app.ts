window.addEventListener('DOMContentLoaded', async () => {
    const sliderOptions = {
        all: {
            enableAutoplay: true,
            stopAutoplayOnInteraction: true,
            autoplayInterval: 5000,
            transitionDuration: 1000,
            enablePagination: false,
        },
    };

    const sliders = document.getElementsByClassName('blaze-slider') as HTMLCollectionOf<HTMLElement>;

    if (sliders.length) {
        let BlazeSlider = await import('blaze-slider');
        BlazeSlider = BlazeSlider.default;
        for ( var i = 0; i < sliders.length; i++ ) {
            new BlazeSlider(sliders[i], sliderOptions);
        }
    }
});
