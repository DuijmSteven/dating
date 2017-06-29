<div class="Tile Shoutbox">
    <div class="Tile__heading Shoutbox__heading">
        Shoutbox
    </div>
    <div class="Tile__body Shoutbox__body">
        <div class="Shoutbox__items">
            @foreach($messages as $message)
                <div class="Shoutbox__item">
                    <div class="Shoutbox__profile-image">
                        <a href="{!! route('users.show', ['userId' => $message['user']->id]) !!}">
                            <img src="{!! \StorageHelper::profileImageUrl($message['user'], true) !!}" alt="">
                        </a>
                    </div>
                    <div class="Shoutbox__bubble">
                        {{ $message['text'] }}
                    </div>
                </div>
            @endforeach
        </div>
        <div class="Shoutbox__controls">
            <form action="">
                <div class="form-group">
                    <textarea name="message"
                              id="message"
                              rows="4"
                              placeholder="Type something!"
                              class="form-control"></textarea>
                </div>
                <div class="text-right">
                    <button type="submit" class="Button Button--primary"><span class="Button__content">POST</span></button>
                </div>
            </form>
        </div>
    </div>
</div>