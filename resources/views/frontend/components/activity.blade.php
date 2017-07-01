<div class="Tile Activity">
    <div class="Tile__heading Activity__heading">
        <div class="Activity__profile-image">
            <img src="{{ $activityThumbnailUrl }}" alt="">
        </div>
        <div class="Activity__user-details">
            <div class="Activity__title">{{ $activityTitle }}</div>
            <div class="Activity__date">{{ $activityDate }}</div>
        </div>
    </div>
    <div class="Tile__body Activity__body">
        @if(isset($activityImageUrl))
            <img src="{{ $activityImageUrl }}" alt="">
        @endif
        @if(isset($activityText))
            <div class="Activity__text">
                {{ $activityText }}
            </div>
        @endif
    </div>
</div>