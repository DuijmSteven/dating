<div class="notes box box-{!! $userClassName !!}">
    <div class="box-header with-border">
        <h3 class="box-title">Notes</h3>
    </div>
    <div class="notes_body box-body">
        <div class="box-group" id="accordion{!! $moduleId !!}">
            <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
            @php
                $currentCategory = null;
                $notesAmount = $notes->count();
            @endphp

            @if($notesAmount == 0)
                <div class="box-header">
                    <p>No notes available...</p>
                </div>
            @else
                @foreach($notes as $note)
                    @if(ucfirst($currentCategory) !== ucfirst($note->category_id))
                        @if(!is_null($currentCategory))
                                </div>
                            </div>
                        </div>
                        @endif
                        @php
                            $currentCategory = $note->category_id;
                        @endphp
                        <div class="panel box">
                            <div class="box-header with-border notes_category-header">
                                <h4 class="notes_category-title box-title">
                                    <a data-toggle="collapse" data-parent="#accordion{!! $moduleId !!}" href="#collapse{!! $moduleId !!}{!! $loop->index !!}" aria-expanded="false" class="collapsed">
                                        <i class="fa fa-fw fa-angle-down"></i>{!! ucfirst($note->noteCategory->name) !!}
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse{!! $moduleId !!}{!! $loop->index !!}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                <div class="box-body">
                    @endif
                                    <div class="note_body {!! ($loop->index == 0) ? 'first' : '' !!} {!! ($loop->index == $notesAmount - 1) ? 'last' : '' !!}">
                                        <div>
                                            <form class="note_form-delete" action="{!! route('backend.conversations.notes.destroy', ['noteId' => $note->id]) !!}" method="POST">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="_method" value="DELETE">

                                                @if($authenticatedUser->isAdmin())
                                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');">
                                                        <i class="fa fa-close note_delete-fa"></i>
                                                    </button>
                                                @endif
                                            </form>

                                        </div>
                                        <p> <em>{!! $note->body !!} </em></p>
                                        <div class="note_date">{!! $note->created_at->diffForHumans() !!}</div>
                                    </div>
                @endforeach
                                </div>
                            </div>
                        </div>
            @endif
        </div>
    </div>
    <div class="text-right">
        @include('backend.conversations.partials.notes-modal-button', ['userId' => $userId])
    </div>
</div>