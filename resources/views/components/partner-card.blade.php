@props(['data']))
@if(count($data))
    <div class="panel-partner">
        <div class="uk-container uk-container-center">
            <div class="wrapper">
                <div class="panel-head">
                    <h3 class="heading-2 wow fadeInDown" data-wow-delay="0.1s">{!! $data['name'] !!}</span></h3>
                    <div class="description wow fadeInDown" data-wow-delay="0.15s">{{ $data['short_code'] }}</div>
                </div>
                <div class="panel-body">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            @php 
                                $time = 0.2;
                            @endphp
                            @foreach($data['item'] as $key => $val )
                                <div class="swiper-slide">
                                    <div class="slide-item wow fadeInDown" data-wow-delay="{{ $time * ($key + 1) }}s">
                                        <a href="" class="image img-cover">
                                            <img src="{{ $val['image'] }}" alt="">
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif