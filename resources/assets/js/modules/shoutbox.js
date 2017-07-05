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

function initiateTextareaCounter()
{
    $('.JS--Shoutbox__controls textarea').keyup(function () {
        if ($(this).val().length >=140) {

        }

        $('.JS--Shoutbox__textarea-counter__count').text($(this).val().length);
    });
}

initiateTextareaCounter();
