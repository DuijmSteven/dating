@extends('admin.layouts.default.layout')


@section('content')

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Article</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{!! route('admin.articles.update', ['articleId' => $article->getId()]) !!}" enctype="multipart/form-data">
            {!! csrf_field() !!}
            {!! method_field('PUT') !!}
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text"
                                   class="form-control"
                                   id="title"
                                   name="title"
                                   required
                                   value="{{ $article->getTitle() }}"
                            >
                            </input>
                            @if ($errors->has('title'))
                                {!! $errors->first('title', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status"
                                    id="status"
                                    class="form-control"
                                    required
                            >
                                <option value="1" {!! ($article->getStatus() === 1) ? 'selected' : '' !!}>Public</option>
                                <option value="0" {!! ($article->getStatus() === 0) ? 'selected' : '' !!}>Private</option>
                            </select>
                            @if ($errors->has('status'))
                                {!! $errors->first('status', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="article_image">Image</label>
                            <input type="file" class="form-control" id="article_image" name="article_image">
                            @if ($errors->has('article_image'))
                                {!! $errors->first('article_image', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>

                        <h4>Image preview</h4>
                        @if($article->image_filename)
                            <div class="img-responsive">
                                <img class="Article__image" src="{!! \StorageHelper::articleImageUrl($article->id, $article->image_filename, false) !!}" alt="Article image">
                            </div>
                        @else
                            No image set yet.
                        @endif


                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control"
                                      id="body"
                                      name="body"
                                      required
                                      rows="20"
                                      data-provide="markdown"
                            >{!! $article->getBody() !!}</textarea>
                            @if ($errors->has('body'))
                                {!! $errors->first('body', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="body">Meta Description</label>
                            <textarea class="form-control"
                                      id="meta_description"
                                      name="meta_description"
                                      required
                                      rows="20"
                                      data-provide="markdown"
                            >{!! $article->getMetaDescription() !!}</textarea>
                            @if ($errors->has('meta_description'))
                                {!! $errors->first('meta_description', '<small class="form-error">:message</small>') !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-right">
                <button type="submit" class="btn btn-primary">Update Article</button>
            </div>
        </form>
    </div>

@endsection
