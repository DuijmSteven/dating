<?php $count = 0; ?>
@foreach($users as $user)
    <div class="col-sm-4">
        <h3>
            {{ $user->username }}{{ isset($user->meta->dob) ? ', ' . $user->meta->dob->diffInYears($carbonNow) : '' }}
        </h3>
        <img style="width: 100%;" src="http://placehold.it/300x300" alt="user image">

        <strong>Username: </strong>{{ $user->username}}<br>
        <strong>Email: </strong>{{ $user->email }}<br>

        <h3>User meta</h3>
        <strong>Gender: </strong>{{ $user->meta->gender }}<br>
        <strong>Relationship status: </strong>{{ $user->meta->relationship_status }}<br>
        <strong>Body type: </strong>{{ $user->meta->body_type }}<br>
        <strong>Eye color: </strong>{{ $user->meta->eye_color }}<br>
        <strong>Hair color: </strong>{{ $user->meta->hair_color }}<br>
        <strong>Smoking habits: </strong>{{ $user->meta->smoking_habits }}<br>
        <strong>Drinking habits: </strong>{{ $user->meta->drinking_habits }}<br>
    </div>
    <?php $count++; ?>
    @if($count % 3 == 0)
        <div class="col-xs-12">
        </div>
    @endif
@endforeach