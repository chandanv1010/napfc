<div class="mobile-header uk-hidden-large">
    <div class="mobile-upper">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="mobile-logo">
                    <a href="." title="{{ $system['seo_meta_title'] }}">
                        <img src="{{ $system['homepage_logo'] }}" alt="Mobile Logo">
                    </a>
                    {{-- <form action="tim-kiem" class="search">
                        <input type="text" name="keyword" placeholder="Tìm kiếm">
                        <button type="submit" class="btn-search">
                            <img src="/vendor/frontend/img/search.svg" alt="">
                        </button>
                    </form> --}}
                </div>
                <div class="tool">
                    <div class="menu-link">
                        <a href="#mobileCanvas" class="mobile-menu-button" data-uk-offcanvas="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 448 512" class="w-6 h-6 cursor-pointer  pl-3 box-content"><path d="M0 88c0-13.3 10.7-24 24-24h400c13.3 0 24 10.7 24 24s-10.7 24-24 24H24c-13.3 0-24-10.7-24-24m0 160c0-13.3 10.7-24 24-24h400c13.3 0 24 10.7 24 24s-10.7 24-24 24H24c-13.3 0-24-10.7-24-24m448 160c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24s10.7-24 24-24h400c13.3 0 24 10.7 24 24"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="mobileCanvas" class="uk-offcanvas offcanvas" >
    <div class="uk-offcanvas-bar" >
        @if(isset($menu['mobile']))
		<ul class="l1 uk-nav uk-nav-offcanvas uk-nav uk-nav-parent-icon" data-uk-nav>
			@foreach ($menu['mobile'] as $key => $val)
                @php
                    $name = $val['item']->languages->first()->pivot->name;
                    $canonical = write_url($val['item']->languages->first()->pivot->canonical, true, true);
                @endphp
                <li class="l1 {{ (count($val['children']))?'uk-parent uk-position-relative':'' }}">
                    <?php echo (isset($val['children']) && is_array($val['children']) && count($val['children']))?'<a href="#" title="" class="dropicon"></a>':''; ?>
                    <a href="{{ $canonical }}" title="{{ $name }}" class="l1">{{ $name }}</a>
                    @if(count($val['children']))
                    <ul class="l2 uk-nav-sub">
                        @foreach ($val['children'] as $keyItem => $valItem)
                        @php
                            $name_2 = $valItem['item']->languages->first()->pivot->name;
                            $canonical_2 = write_url($valItem['item']->languages->first()->pivot->canonical, true, true);
                        @endphp
                        <li class="l2">
                            <a href="{{ $canonical_2 }}" title="{{ $name_2 }}" class="l2">{{ $name_2 }}</a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
			@endforeach
		</ul>
		@endif
	</div>
</div>