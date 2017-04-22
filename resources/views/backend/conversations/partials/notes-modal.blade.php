<div class="modal fade add-note-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="mySmallModalLabel">Small modal</h4></div>
            <div class="modal-body">

                <form class="form" role="form" method="POST" action="{!! route('backend.conversations.notes.store') !!}">
                    {!! csrf_field() !!}
                    <input type="hidden" value="{!! $conversationId !!}" name="conversation_id">
                    <input type="hidden" value="" id="note_user_id" name="user_id">
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input class="form-control" type="text" id="category" name="category">
                    </div>
                    <div class="form-group">
                        <label for="body">Body</label>
                        <textarea class="form-control" name="body" id="body" cols="30" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-flat">Create note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>