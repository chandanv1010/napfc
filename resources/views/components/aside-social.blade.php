@props(['system'])

<div>
    <div class="aside-social aside-panel">
        <div class="aside-heading">Theo dõi chúng tôi</div>
        <div class="aside-body">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-3">
                    <a href="{{ $system['social_facebook'] }}" target="_blank" class="social-button btn-facebook">
                        <span class="icon"><i class="fa fa-facebook"></i></span>
                        <span>LIKE</span>
                    </a>
                </div>
                <div class="uk-width-large-1-3">
                    <a href="{{ $system['social_twitter'] }}" class="social-button btn-twitter">
                        <span class="icon"><i class="fa fa-twitter"></i></span>
                        <span>Follow</span>
                    </a>
                </div>
                <div class="uk-width-large-1-3">
                    <a href="{{ $system['social_youtube'] }}" class="social-button btn-youtube">
                        <span class="icon"><i class="fa fa-twitter"></i></span>
                        <span>Subcribe</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>