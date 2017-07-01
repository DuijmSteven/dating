<?php $count = 0; ?>
@foreach($users as $user)
    <div class="col-sm-4">
        <div class="Tile">
            <div class="Tile__heading">
                {{ $user->username }}{{ isset($user->meta->dob) ? ', ' . $user->meta->dob->diffInYears($carbonNow) : '' }}
            </div>
            <img style="width: 100%;" src="{{ \StorageHelper::profileImageUrl($user) }}" alt="user image">
        </div>
    </div>
    <?php $count++; ?>
    @if($count % 3 == 0)
        <div class="col-xs-12">
            <hr>
        </div>
    @endif
@endforeach