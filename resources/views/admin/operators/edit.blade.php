@extends('admin.layouts.default.layout')


@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Operator</h3>
        </div>

        <div style="margin-bottom: 20px; padding: 10px 10px 0">
            <a href="{!! route('admin.conversations.with-operator', ['operatorId' => $operator->getId()]) !!}" class="btn btn-default">Conversations
                {{--                                        <b>({{ $operator->conversationsAsOperator()->withTrashed()->count() }})</b>--}}
            </a>
            <a href="{!! route('admin.operators.messages.overview', ['operatorId' => $operator->getId()]) !!}" class="btn btn-default">Sent messages <b>({{ $operator->operator_messages_count }})</b></a>

            <form
                method="POST"
                action="{!! route('admin.operators.destroy', ['id' => $operator->getId()]) !!}"
                style="display: inline-block"
            >
                {!! csrf_field() !!}
                {!! method_field('DELETE') !!}
                <button type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this operator?')">
                    Delete
                </button>
            </form>
        </div>


        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.operators.update', ['id' => $operator->id]) !!}"
              enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
            <div class="box-body">
                <div class="userStats">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <h5 class="statsHeading"><strong>Messages sent</strong></h5>
                            <div class="statsBody">
                                <strong>All time:</strong> {!! $operator->operator_messages_count !!} <br>
                                <strong>This month:</strong> {!! $operator->operator_messages_this_month_count !!} <br>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <hr/>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text"
                                   class="form-control"
                                   id="username"
                                   name="username"
                                   required
                                   value="{!! $operator->username !!}"
                            >
                            @if ($errors->has('username'))
                                {!! $errors->first('username', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Email</label>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   value="{!! $operator->email !!}"
                                   required>
                            @if ($errors->has('email'))
                                {!! $errors->first('email', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="first_name"
                                   name="first_name"
                                   required
                                   value="{!! $operator->first_name !!}"
                            >
                            @if ($errors->has('first_name'))
                                {!! $errors->first('first_name', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="last_name"
                                   name="last_name"
                                   required
                                   value="{!! $operator->last_name !!}"
                            >
                            @if ($errors->has('last_name'))
                                {!! $errors->first('last_name', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="postal_code">Postal Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="postal_code"
                                   name="postal_code"
                                   required
                                   value="{!! $operator->postal_code !!}"
                            >
                            @if ($errors->has('postal_code'))
                                {!! $errors->first('postal_code', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="street_name">Street</label>
                            <input type="text"
                                   class="form-control"
                                   id="street_name"
                                   name="street_name"
                                   required
                                   value="{!! $operator->street_name !!}"
                            >
                            @if ($errors->has('street_name'))
                                {!! $errors->first('street_name', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text"
                                   class="form-control"
                                   id="country"
                                   name="country"
                                   required
                                   value="{!! $operator->meta->country !!}"
                            >
                            @if ($errors->has('country'))
                                {!! $errors->first('country', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="active">Active</label>
                            <select name="active"
                                    id="active"
                                    class="form-control"
                                    required
                            >
                                <option value="1" {!! ($operator->active == 1) ? 'selected' : '' !!}>Active</option>
                                <option value="0" {!! ($operator->active == 0) ? 'selected' : '' !!}>Inactive</option>
                            </select>
                            @if ($errors->has('active'))
                                {!! $errors->first('active', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <hr/>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Date of birth:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text"
                                       class="form-control pull-right datepicker__date"
                                       name="dob"
                                       value="{{ $operator->meta->dob ? $operator->meta->dob->format('d-m-Y') : '' }}"
                                >
                                @if ($errors->has('dob'))
                                    {!! $errors->first('dob', '<small class="form-error">:message</small>') !!}
                                @endif
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">City</label>
                            <input type="text"
                                   class="JS--autoCompleteCites form-control"
                                   name="city"
                                   value="{!! ucfirst($operator->meta->city) !!}"
                            >
                            @if ($errors->has('city'))
                                {!! $errors->first('city', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <hr/>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_images">Gallery Images</label>
                            <input type="file" class="form-control" id="user_images" name="user_images[]" multiple>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="table-responsive" id="images-section">
            <table class="table table-striped">
                <?php $tableColumnAmount = 3; ?>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Visible</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="<?= $tableColumnAmount; ?>">
                        Profile Image
                    </td>
                </tr>
                @if($operator->hasProfileImage())
                    <tr>
                        <td>
                            <img width="200" src="{!! \StorageHelper::profileImageUrl($operator) !!}"/>
                        </td>
                        <td>
                            <?= ($operator->profileImage->visible) ? 'Yes' : 'No' ; ?>
                        </td>
                        <td class="action-buttons">
                            <form method="POST" action="{!! route('images.destroy', ['imageId' => $operator->profileImage->id]) !!}">
                                {!! csrf_field() !!}
                                {!! method_field('DELETE') !!}
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="<?= $tableColumnAmount; ?>">
                            No profile image set
                        </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="<?= $tableColumnAmount; ?>">
                        Other Images
                    </td>
                </tr>

                <?php $operatorImagesNotProfile = $operator->imagesNotProfile; ?>
                @if(!is_null($operatorImagesNotProfile))
                    @foreach($operatorImagesNotProfile as $image)
                        <tr>
                            <td>
                                <img width="200" src="{!! \StorageHelper::userImageUrl($operator->id, $image->filename) !!}"/>
                            </td>
                            <td>
                                <?= ($image->visible) ? 'Yes' : 'No' ; ?>
                            </td>
                            <td class="action-buttons">
                                <form method="POST" action="{!! route('images.destroy', ['imageId' => $image->id]) !!}">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <a href="{!! route('users.set-profile-image', ['userId' => $operator->id, 'imageId' => $image->id]) !!}" class="btn btn-success">Set profile</a>
                                <a href="{!! route('images.toggle_visibility', ['imageId' => $image->id]) !!}" class="btn btn-default">Toggle visibility</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="<?= $tableColumnAmount; ?>">
                            No images found
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
