
(function($) {
    
    "use strict";
    var HT = {}; // Khai báo là 1 đối tượng
    var timer;
    var $carousel = $(".owl-slide");
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.swiperOption = (setting) => {
        // console.log(setting);
        let option = {}
        if(setting.animation.length){
            option.effect = setting.animation;
        }	
        if(setting.arrow === 'accept'){
            option.navigation = {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        }
        if(setting.autoplay === 'accept'){
            option.autoplay = {
                delay: 50000,
                disableOnInteraction: false,
            }
        }
        if(setting.navigate === 'dots'){
            option.pagination = {
                el: '.swiper-pagination',
            }
        }
        return option
    }

    /* MAIN VARIABLE */
    HT.swiper = () => {
        var swiper = new Swiper(".panel-slide .swiper-container", {
            loop: false,
            pagination: {
                el: '.swiper-pagination',
            },
            autoplay: {
                delay : 3000,
            },
            spaceBetween: 15,
            slidesPerView: 1.5,
            breakpoints: {
                100: {
                    slidesPerView: 1,
                },
                500: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 1,
                },
                1280: {
                    slidesPerView: 1,
                }
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            
        });
    }


    HT.niceSelect = () => {
        if($('.nice-select').length){
            $('.nice-select').niceSelect();
        }
        
    }

    HT.select2 = () => {
        if($('.setupSelect2').length){
            $('.setupSelect2').select2();
        }
        
    }


    HT.skeleton = () => {
        
        document.addEventListener("DOMContentLoaded", function() {
            // Lựa chọn tất cả các ảnh cần lazy load
            const lazyImages = document.querySelectorAll('.lazy-image');
            
            // Tạo Intersection Observer
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    // Khi phần tử trở nên visible
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        // Lấy nguồn ảnh từ thuộc tính data-src
                        const src = img.dataset.src;
                        
                        // Tạo ảnh mới và thiết lập trình xử lý sự kiện onload
                        const newImg = new Image();
                        newImg.onload = function() {
                            // Khi ảnh đã tải xong, gán src và thêm class loaded
                            img.src = src;
                            img.classList.add('loaded');
                            
                            // Ẩn skeleton loading
                            const parent = img.closest('.image');
                            if (parent) {
                                const skeleton = parent.querySelector('.skeleton-loading');
                                if (skeleton) {
                                    skeleton.style.display = 'none';
                                }
                            }
                            
                            // Ngừng quan sát phần tử này
                            observer.unobserve(img);
                        };
                        
                        // Bắt đầu tải ảnh
                        newImg.src = src;
                    }
                });
            }, {
                // Tùy chọn: thiết lập ngưỡng và root
                rootMargin: '0px 0px 50px 0px', // Tải trước ảnh khi chúng cách 50px từ viewport
                threshold: 0.1 // Kích hoạt khi ít nhất 10% của ảnh trở nên visible
            });
            
            // Quan sát mỗi ảnh
            lazyImages.forEach(img => {
                observer.observe(img);
            });
        });
    }


    HT.removePagination = () => {
        $('.filter-content').on('slide', function() {
            $('.uk-flex .pagination').hide();
        });
    };


    HT.wrapTable = () => {
        var width = $(window).width()
        if(width < 600){
            $('table').wrap('<div class="uk-overflow-container"></div>')
        }
    }

    HT.addVoucher = () => {
        $(document).on('click','.info-voucher', function(e){
            e.preventDefault()
            let _this = $(this)
            _this.toggleClass('active');
        })
    }


    HT.scroll = () => {
        $(document).ready(function() {
            $('a[href="#panel-contact"]').on('click', function(event) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: $('#panel-contact').offset().top - 50
                }, 800); 
            });
        });
    }



    HT.scrollHeading = () => {
        $(document).on('click', '.widget-toc a', function(e) {
            e.preventDefault(); // Ngăn hành vi mặc định của thẻ a
            
            let _this = $(this);
            let href = _this.attr('href'); // Lấy giá trị href
            
            // Kiểm tra nếu href bắt đầu bằng #
            if (href && href.startsWith('#')) {
                let targetId = href.substring(1); // Loại bỏ dấu # đầu tiên
                
                // Sử dụng document.getElementById thay vì jQuery selector để tránh lỗi
                let targetElement = document.getElementById(targetId);
                
                // Kiểm tra xem element có tồn tại không
                if (targetElement) {
                    // Chuyển về jQuery object để sử dụng offset()
                    let $targetElement = $(targetElement);
                    
                    // Cuộn mượt đến element
                    $('html, body').animate({
                        scrollTop: $targetElement.offset().top - 100 // Trừ 100px để tạo khoảng cách
                    }, 800); // 800ms cho hiệu ứng cuộn mượt
                    
                    // Thêm class active cho liên kết được click
                    $('.widget-toc a').removeClass('active');
                    _this.addClass('active');
                } else {
                    console.log('Không tìm thấy element với ID:', targetId);
                }
            }
        });
    }


    HT.highlightTocOnScroll = () => {
        $(window).on('scroll', function() {
            let scrollTop = $(window).scrollTop();
            
            $('.widget-toc a').each(function() {
                let href = $(this).attr('href');
                if (href && href.startsWith('#')) {
                    let targetId = href.substring(1);
                    let targetElement = document.getElementById(targetId); // Sử dụng getElementById
                    
                    if (targetElement) {
                        let $targetElement = $(targetElement);
                        let elementTop = $targetElement.offset().top - 150;
                        let elementBottom = elementTop + $targetElement.outerHeight();
                        
                        if (scrollTop >= elementTop && scrollTop < elementBottom) {
                            $('.widget-toc a').removeClass('active');
                            $(this).addClass('active');
                        }
                    }
                }
            });
        });
    }



    HT.popupSwiperSlide = () => {
        document.querySelectorAll(".popup-gallery").forEach(popup => {
            var swiper = new Swiper(popup.querySelector(".swiper-container"), {
                loop: true,
                // autoplay: {
                // 	delay: 2000,
                // 	disableOnInteraction: false,
                // },
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: {
                    swiper: {
                        el: popup.querySelector('.swiper-container-thumbs'),
                        slidesPerView: 4,
                        spaceBetween: 10,
                        slideToClickedSlide: true,
                    }
                }
            });
        });
    }


    $(document).ready(function(){
    
        HT.highlightTocOnScroll();
        HT.scrollHeading()
        HT.scroll()
        HT.addVoucher()
        HT.removePagination()

        
        /* CORE JS */
        HT.swiper()
        HT.niceSelect()		
        HT.select2()
        // HT.loadDistribution()
        HT.wrapTable()
        // HT.service()
        HT.skeleton()

        /** ACTION  */
        HT.register()
        HT.previewVideo()
        // HT.filterCourse()


        /** SLIDES */

        HT.major()
        HT.partner()

        // $(window).on('load', function() {
        //     HT.swiper();
        // });
    });


})(jQuery);
