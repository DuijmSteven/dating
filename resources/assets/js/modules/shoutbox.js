function disableBodyScrollOnShoutboxHover() {
    var bodySelector = $('body');

    $('.JS--Shoutbox__body').hover(
        function () {
            bodySelector.css('position', 'fixed');
            bodySelector.css('overflow-y', 'scroll');
        },
        function () {
            bodySelector.css('position', 'static');
            bodySelector.css('overflow-y', 'auto');
        }
    );
}

disableBodyScrollOnShoutboxHover();
