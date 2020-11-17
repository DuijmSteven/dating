@extends('admin.layouts.default.layout')


@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Editor</h3>
        </div>

        <div style="margin-bottom: 20px; padding: 10px 10px 0">
            <a href="{!! route('admin.editors.created-bots.overview', [$editor->getId()]) !!}" class="btn btn-default">Created bots <b>({{ $editor->created_bots_count }})</b></a>

            <form
                method="POST"
                action="{!! route('admin.editors.destroy', ['id' => $editor->getId()]) !!}"
                style="display: inline-block"
            >
                {!! csrf_field() !!}
                {!! method_field('DELETE') !!}
                <button type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this editor?')">
                    Delete
                </button>
            </form>
        </div>

        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.editors.update', ['userId' => $editor->id]) !!}"
              enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
            <div class="box-body">
                <div class="userStats">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <h5 class="statsHeading"><strong>Created bots</strong></h5>
                            <div class="statsBody">
                                <strong>All time:</strong> {!! $editor->created_bots_count !!} <br>
                                <strong>Last month:</strong> {!! $editor->created_bots_last_month_count !!} <br>
                                <strong>This month:</strong> {!! $editor->created_bots_this_month_count !!} <br>
                                <strong>Last week:</strong> {!! $editor->created_bots_last_week_count !!} <br>
                                <strong>This week:</strong> {!! $editor->created_bots_this_week_count !!} <br>
                                <strong>Yesterday:</strong> {!! $editor->created_bots_yesterday_count !!} <br>
                                <strong>Today:</strong> {!! $editor->created_bots_today_count !!} <br>
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
                                   value="{!! $editor->username !!}"
                            >
                            @if ($errors->has('username'))
                                {!! $errors->first('username', '<small class="form-error">:message</small>') !!}
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
                                <option value="1" {!! ($editor->active == 1) ? 'selected' : '' !!}>Active</option>
                                <option value="0" {!! ($editor->active == 0) ? 'selected' : '' !!}>Inactive</option>
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
                                       value="{{ $editor->meta->dob ? $editor->meta->dob->format('d-m-Y') : '' }}"
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
                                   value="{!! ucfirst($editor->meta->city) !!}"
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
                @if($editor->hasProfileImage())
                    <tr>
                        <td>
                            <img width="200" src="{!! \StorageHelper::profileImageUrl($editor) !!}"/>
                        </td>
                        <td>
                            <?= ($editor->profileImage->visible) ? 'Yes' : 'No' ; ?>
                        </td>
                        <td class="action-buttons">
                            <form method="POST" action="{!! route('images.destroy', ['imageId' => $editor->profileImage->id]) !!}">
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

                <?php $editorImagesNotProfile = $editor->imagesNotProfile; ?>
                @if(!is_null($editorImagesNotProfile))
                    @foreach($editorImagesNotProfile as $image)
                        <tr>
                            <td>
                                <img width="200" src="{!! \StorageHelper::userImageUrl($editor->id, $image->filename) !!}"/>
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
                                <a href="{!! route('users.set-profile-image', ['userId' => $editor->id, 'imageId' => $image->id]) !!}" class="btn btn-success">Set profile</a>
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
