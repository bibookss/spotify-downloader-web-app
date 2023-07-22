import './bootstrap';
import 'flowbite';


// To make horizontally overflowed divs scrollable with mousewheel :>
$(document).ready(function () {
    $('.my-scroll').on('wheel', function (e) {
        e.preventDefault();

        var delta = e.originalEvent.deltaY;
        $(this).scrollLeft($(this).scrollLeft() + delta);
    });
});