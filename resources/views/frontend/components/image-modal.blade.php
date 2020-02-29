<div class="modal fade imageModal" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="material-icons">
                        close
                    </i>
                </button>

                <img class="{{ !$authenticatedUser->isPayingUser() ? 'very-blurred' : '' }}" alt="imagePreview" src="" id="imagePreview" style="width: 100%" >
            </div>
        </div>

        @if(!$authenticatedUser->isPayingUser())
            <div class="nonPayingUserMessage">
                @include('frontend.components.button', [
                        'buttonContext' => 'general',
                        'buttonState' => 'primary',
                        'buttonText' => trans('navbar.credits'),
                        'buttonClasses' => 'centered Button--tall Button--highlighted',
                        'url' => route('credits.show'),
                    ])
            </div>
        @endif
    </div>

    <i class="material-icons imageModalArrow leftArrow hidden JS--imageModalArrow JS--leftArrow">
        chevron_left
    </i>

    <i class="material-icons imageModalArrow rightArrow hidden JS--imageModalArrow JS--rightArrow">
        chevron_right
    </i>
</div>