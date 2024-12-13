import Splide from '@splidejs/splide';
var elms = document.getElementsByClassName( 'splide' );

for ( var i = 0; i < elms.length; i++ ) {
    new Splide( elms[ i ] ).mount();
}
/* todo почему не работает при динамическом импорте?
window.addEventListener('DOMContentLoaded', () => {
    const sliders = document.getElementsByClassName('js-splide');
    if (sliders.length) {
        import('@splidejs/splide').then((Splide) => {
            for ( var i = 0; i < sliders.length; i++ ) {
                new Splide(sliders[ i ]).mount();
            }
        })
    }
});
*/
