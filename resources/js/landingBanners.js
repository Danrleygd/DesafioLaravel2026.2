document.addEventListener('DOMContentLoaded', function () {

    const slides = document.querySelectorAll(
        '.landing-main-slide'
    );

    const dots = document.querySelectorAll(
        '.landing-slider-dots button'
    );

    if (slides.length <= 1) {
        return;
    }

    let slideAtual = 0;

    function mostrarSlide(index) {

        slides.forEach(function (slide, i) {

            slide.classList.toggle(
                'active',
                i === index
            );

        });

        dots.forEach(function (dot, i) {

            dot.classList.toggle(
                'active',
                i === index
            );

        });

        slideAtual = index;
    }


    function proximoSlide() {

        let proximo = slideAtual + 1;

        if (proximo >= slides.length) {
            proximo = 0;
        }

        mostrarSlide(proximo);
    }


    dots.forEach(function (dot) {

        dot.addEventListener('click', function () {

            const index = Number(
                this.dataset.slideTo
            );

            mostrarSlide(index);

        });

    });


    mostrarSlide(0);


    setInterval(function () {

        proximoSlide();

    }, 5000);

});