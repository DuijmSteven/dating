function disableBodyScrollOnShoutboxHover() {
    $('.JS--Shoutbox__body').hover(
        function () {
            $('body').css('overflow', 'hidden');
        },
        function () {
            $('body').css('overflow', 'auto');
        }
    );
}

disableBodyScrollOnShoutboxHover();
