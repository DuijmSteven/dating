<div class="modal fade ConfirmModal ConfirmModal{{ $modalId }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button> <h4 class="modal-title">Modal title</h4>
            </div>

            <div class="modal-body">

                <p>{{ $body ?? '' }}</p>

                <div class="ConfirmModal__actionButtons">
                    @include('frontend.components.button', [
                          'url' => $url,
                          'buttonContext' => 'general',
                          'buttonState' => 'danger',
                          'buttonText' => @trans(config('app.directory_name') . '/edit_profile.deactivate')
                      ])

                    <button type="button" class="pull-right Button Button--default" data-dismiss="modal">
                        <span class="Button__content">
                            {{ trans(config('app.directory_name') . '/buttons.cancel') }}
                        </span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>