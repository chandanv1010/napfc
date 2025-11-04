<!-- ASIDE PRODUCT -->
<aside class="aside">
    {{-- DANH MỤC SẢN PHẨM --}}
    @php
        $category = \App\Models\ProductCatalogue::where('publish', 2)
            ->orderByDesc('order')
            ->orderByDesc('id')
            ->with(['languages'])
            ->get();

        $category = recursive($category);
        
        // dd($category);
    @endphp

    @if(!empty($category))
        @foreach($category as $val)
        @php
            $catName = $val['item']->languages->first()->pivot->name;
        @endphp
            <section class="aside-category aside-panel">
                <div class="aside-heading"><span>{{ $catName }}</span></div>
                <div class="aside-body">
                    @if(!empty($val['children']))
                        <ul class="uk-list uk-clearfix list-cat">
                            @foreach($val['children'] as $valChildren)
                                @php
                                    $name = $valChildren['item']->languages->first()->pivot->name;
                                    $canonical = write_url($valChildren['item']->languages->first()->pivot->canonical);
                                @endphp
                                <li>
                                    <a href="{{ $canonical }}" title="{{ $name }}" class="uk-position-relative">
                                        <span>{{ $name }}</span>
                                        @if(!empty($valChildren['children']))
                                            <span class="btn_dropdown_sp">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </span>
                                        @endif
                                    </a>

                                    @if(!empty($valChildren['children']))
                                        <ul class="uk-clearfix children" style="display:none">
                                            @foreach($valChildren['children'] as $valS)
                                             @php
                                                $nameS = $valS['item']->languages->first()->pivot->name;
                                                $canonicalS = write_url($valS['item']->languages->first()->pivot->name);
                                            @endphp
                                                <li>
                                                    <a href="{{ $canonicalS }}" title="{{ $nameS }}">{{ $nameS }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </section>
        @endforeach
    @endif


   <section class="aside-panel aside-product">
        <div class="aside-heading">
            <span>Hỗ trợ Kinh doanh</span>
        </div>
        <div class="aside-body">
            <ul class="uk-list uk-clearfix">
                <li class="mb15">
                    <div class="supp-odd supporter uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="supporter-info">
                            <div class="supporter-name">Mr Phát <a href="tel:0961 039 666" title="0961 039 666">0961 039 666</a>
                            </div>
                        </div>
                        <div class="supporter-online">
                            <a href="skype:thuynv_1308?chat" title="Skype" class="img-scaledown">
                                <img src="skype.png">
                            </a>
                        </div>
                    </div>
                </li>
                <li class="mb15">
                    <div class="supp-odd supporter uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="supporter-info">
                            <div class="supporter-name">Mr Nam <a href="tel:0912 64 3333" title="0912 64 3333">0912 64 3333</a>
                            </div>
                        </div>
                        <div class="supporter-online">
                            <a href="skype:?chat" title="Skype" class="img-scaledown">
                                <img src="skype.png">
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    <section class="aside-panel aside-product">
        <div class="aside-heading">
            <span>Hỗ trợ Kỹ thuật</span>
        </div>
        <div class="aside-body">
            <ul class="uk-list uk-clearfix">
                <li class="mb15">
                    <div class="supp-odd supporter uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="supporter-info">
                            <div class="supporter-name">Mr Phát <a href="tel:0961 039 666" title="0961 039 666">0961 039 666</a>
                            </div>
                        </div>
                        <div class="supporter-online">
                            <a href="skype:thuynv_1308?chat" title="Skype" class="img-scaledown">
                                <img src="skype.png">
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    {{-- SẢN PHẨM MỚI --}}
    @php
        $products = \App\Models\Product::where('publish', 2)
            ->with(['languages'])
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();


    @endphp

    @if($products->isNotEmpty())
        <section class="aside-panel aside-product">
            <div class="aside-heading"><span>Sản phẩm mới</span></div>
            <div class="aside-body">
                @foreach($products as $val)
                    @php
                        $href = write_url($val->languages->first()->pivot->canonical);
                        $name = $val->languages->first()->pivot->name;
                    @endphp
                    <div class="aside-item">
                        <a href="{{ $href }}" class="image img-cover" title="{{ $name }}">
                            <img src="{{ $val->image }}" alt="{{ $name }}">
                        </a>
                        <h3 class="title">
                            <a href="{{ $href }}" title="{{ $name }}">{{ $name }}</a>
                        </h3>
                        <div class="price">Giá: Liên Hệ</div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</aside>

{{-- SCRIPT DROPDOWN --}}
<script>
	$(document).ready(function() {
		$(document).on('click', '.btn_dropdown_sp', function(event) {
			event.preventDefault();
			$(this).toggleClass('active');
			$(this).closest('li').find('.children').slideToggle();
			return false;
		});
	});		
</script>    
