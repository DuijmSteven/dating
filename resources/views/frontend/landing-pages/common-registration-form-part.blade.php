<input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
<input type="hidden" name="action" value="validate_captcha">

@if(isset($mediaId))
    <input type="hidden" name="mediaId" value="{{ $mediaId }}">
@endif

@if(isset($registrationKeyword))
    <input type="hidden" name="registrationKeyword" value="{{ $registrationKeyword }}">
@endif

@if(isset($publisher))
    <input type="hidden" name="publisher" value="{{ $publisher }}">
@endif

@if(isset($clickId))
    <input type="hidden" name="clickId" value="{{ $clickId }}">
    <input type="hidden" name="affiliate" value="{{ $affiliate }}">
    <input type="hidden" name="country" value="{{ $country }}">
@elseif(isset($affiliate) && $affiliate === \App\UserAffiliateTracking::AFFILIATE_DATECENTRALE)
    <input type="hidden" name="affiliate" value="{{ \App\UserAffiliateTracking::AFFILIATE_DATECENTRALE }}">
@elseif(isset($affiliate) && $affiliate === \App\UserAffiliateTracking::AFFILIATE_DATINGSITELIJSTPROMO)
    <input type="hidden" name="affiliate" value="{{ \App\UserAffiliateTracking::AFFILIATE_DATINGSITELIJSTPROMO }}">
@endif