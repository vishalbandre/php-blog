flag = false;
document.querySelector('.menu-toggle').addEventListener('click', () => {
    if (!flag) {
        document.querySelector('.menu-toggle').classList.add('menu-untoggle');
        document.querySelector('.menu').setAttribute("style", "display: flex;");
        flag = true;
    } else {
        document.querySelector('.menu-toggle').classList.remove('menu-untoggle');
        document.querySelector('.menu').setAttribute("style", "display: none;");
        flag = false;
    }
});

var element = document.getElementsByClassName('splide');
if (element.length > 0) {
    new Splide('.splide', {
        type: 'loop',
        autoplay: true,
        padding: {
            right: '5rem',
            left: '5rem',
        },
    }).mount();
}