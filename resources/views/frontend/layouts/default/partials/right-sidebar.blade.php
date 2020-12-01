<div class="Layout-part Right-sidebar">
    @if(
        (int) config('app.site_id') === \App\Helpers\SiteHelper::SWEETALK_NL &&
        Route::currentRouteNamed('home')
    )
        <public-chat></public-chat>
    @endif

{!! $rightSidebarHtml !!}
</div>