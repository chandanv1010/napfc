// const UIkit = require("uikit");

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

    HT.major = () => {

        console.log($('.homepage-news').length);
        

        var swiper = new Swiper(".homepage-news .swiper-container", {
            loop: false,
            pagination: {
                el: '.swiper-pagination',
            },
            autoplay: {
                delay : 2000,
            },
            spaceBetween: 15,
            slidesPerView: 1.5,
            breakpoints: {
                415: {
                    slidesPerView: 1,
                },
                500: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1280: {
                    slidesPerView: 3,
                }
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            
        });

        console.log(swiper);
        
        
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


    HT.advise = () => {
        $(document).on('click','.suggest-aj button', function(e){
            e.preventDefault()
            let _this = $(this)
            let option  = {
                name : $('#suggest input[name=name]').val(),
                gender : $('#suggest input[name=gender]').val(),
                phone : $('#suggest input[name=phone]').val(),
                address: $('#suggest input[name=address]').val(),
                post_id : $('#suggest input[name=post_id ]').val(),
                product_id : $('#suggest input[name=product_id ]').val(),
                _token: _token,
            }
            toastr.success('Gửi yêu cầu thành công , chúng tôi sẽ sớm liên hệ vs bạn !', 'Thông báo từ hệ thống')
            $.ajax({
                url: 'ajax/contact/advise', 
                type: 'POST', 
                data: option, 
                dataType: 'json', 
                beforeSend: function() {
                    
                },
                success: function(res) {
                    console.log(res)
                    if(res.code === 10){
                        
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }else if(res.status === 422){
                        let errors = res.messages;
                        for(let field in errors){
                            let errorMessage = errors[field];
                            $('.'+ field + '-error').text(errorMessage);
                        }
                    }
                },
            });
            
        })
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

    HT.chooseGarenaCard = () => {
        $(document).on('click', '.garena-item', function () {
            const _this = $(this);
            const card = JSON.parse(_this.attr('data-card'));
            const price = parseFloat(card.price);
            const name = card.languages[0]?.name ?? 'Thẻ Garena';
            const formattedPrice = price.toLocaleString('vi-VN') + ' ₫';

            // Kiểm tra trạng thái đăng nhập (Laravel render sẵn)
            const isLoggedIn = window.isCustomerLoggedIn || false;
            const loginUrl = window.loginUrl || '/dang-nhap.html';

            // Chuẩn bị nội dung HTML đè vào .card-description
            let html = `
                <div class="card-order">
                    <h2 class="heading-1">Chi tiết đơn hàng</h2>
                    <div class="order-info">
                        <div class="label">
                            <span class="text">Tên sản phẩm: </span>
                            <span class="value">${name}</span>
                        </div>
                        <div class="label">
                            <span class="text">Đơn giá: </span>
                            <span class="value">${formattedPrice}</span>
                        </div>
                        <div class="label">
                            <span class="text">Số lượng:</span>
                            <span class="value">1</span>
                        </div>
                        <div class="label">
                            <span class="text">Tổng tiền:</span>
                            <span class="value">${formattedPrice}</span>
                        </div>
                        <div class="account-input">
                            <input type="text" id="account-input" class="input-text" placeholder="Nhập vào tài khoản muốn nạp..">
                        </div>
            `;

            if (!isLoggedIn) {
                html += `
                    <button class="buy-or-login" onclick="window.location.href='${loginUrl}'">
                        <div class="main-text">Đăng nhập ngay</div>
                        <div class="sub-text">Vui lòng đăng nhập để tiếp tục</div>
                    </button>
                `;
            } else {
                html += `
                    <a href="#" 
                        class="buy-or-login btn-pay" 
                        data-id="${card.id}" 
                        data-price="${price}" 
                        data-name="${name}">
                            <div class="main-text">Thanh toán ngay</div>
                            <div class="sub-text">Thanh toán số tiền ${formattedPrice}</div>
                    </a>
                `;
            }

            html += `
                        <div class="notice">
                            Nếu bạn muốn nạp số dư nhiều hơn để sử dụng cho những lần mua hàng tiếp theo,
                            vui lòng truy cập trang Nạp số dư <a href="/nap-so-du">tại đây</a>.
                        </div>
                    </div>
                </div>
            `;

            // Đè nội dung mới vào .card-description
            $('.garena-item').removeClass('active')
            _this.addClass('active')
            $('.card-description').html(html);
        });
    };


    let checkStatus = null;
    let lastStatus = null;

    HT.buyAccount = () => {
        $(document).off('click', '.btn-buy-account').on('click', '.btn-buy-account', function (e) {
            e.preventDefault();

            // 🔁 Dừng polling cũ nếu có
            if (checkStatus) {
                clearInterval(checkStatus);
                checkStatus = null;
            }
            lastStatus = null;

            const _this = $(this);
            const oldButton = _this.html();
            const id = _this.data('id');
            const option = {
                id,
                _token
            };

            $.ajax({
                url: 'ajax/account/buy',
                type: 'POST',
                data: option,
                dataType: 'json',
                beforeSend: function () {
                    $('#qr_image').attr('src', '');
                    _this.prop('disabled', true).html(`
                        <div>
                            <div style="color:#fff;font-size:16px;text-transform:uppercase;font-weight:bold">
                                Đang tạo giao dịch...
                            </div>
                            <div style="color:#fff;">Vui lòng chờ trong giây lát</div>
                        </div>
                    `);
                },
                success: function (res) {
                    if (!res.success) {
                        toastr.error(res.data?.message || 'Không thể khởi tạo giao dịch', 'Lỗi');
                        return;
                    }

                    const data = res.data;
                    const $qrImage = $('#qr_image');
                    $qrImage.attr('src', data.qr_image);

                    $qrImage.off('load').on('load', function () {
                        const modal = UIkit.modal('.qrcodeModal');
                        modal.show();

                        //  Polling trạng thái
                        let waited = 0;
                        const maxWait = 600; // 10 phút (tính bằng giây)

                        checkStatus = setInterval(() => {
                            waited += 5;

                            $.get(`/ajax/account/status/${data.transaction_code}`, function (resp) {
                                if (!resp.success) return;

                                // Tránh spam toastr
                                if (resp.status !== lastStatus) {
                                    lastStatus = resp.status;

                                    if (resp.status === 'paid') {
                                        clearInterval(checkStatus);
                                        checkStatus = null;
                                        modal.hide();
                                        toastr.success('Thanh toán thành công!');
                                        window.location.href = `/account/info/success/${data.transaction_code}`;
                                    } else if (resp.status === 'expired' || resp.status === 'invalid') {
                                        clearInterval(checkStatus);
                                        checkStatus = null;
                                        modal.hide();
                                        toastr.info('Giao dịch đã hết hạn, vui lòng tạo lại.');
                                    }
                                }
                            });

                            if (waited >= maxWait) {
                                clearInterval(checkStatus);
                                checkStatus = null;
                                toastr.info('Hết thời gian chờ thanh toán, vui lòng tạo lại giao dịch.');
                            }
                        }, 5000); // Mỗi 5 giây kiểm tra 1 lần
                    });
                },
                complete: function () {
                    _this.prop('disabled', false).html(oldButton);
                }
            });
        });
    };

  
    HT.payCard = () => {
        $(document).off('click', '.btn-pay').on('click', '.btn-pay', function(e) {
            let _this = $(this)
            var qrCodeModal = UIkit.modal(".qrcodeModal");

            const oldButton = _this.html()

            const id = _this.attr('data-id')
            const amount = parseFloat(_this.attr('data-price'))
            const account = $('#account-input').val()?.trim().toLowerCase()
            const timestamp = Date.now()
            const customerId = window.customerId

            if (!account) {
                alert('Bạn chưa nhập vào account muốn nạp')
                return;
            }

            let option  = {
                id,
                _token,
                account,
                amount,
                customerId
            }
            // toastr.success('Gửi yêu cầu thành công , chúng tôi sẽ sớm liên hệ vs bạn !', 'Thông báo từ hệ thống')
            $.ajax({
                url: 'ajax/transaction/create', 
                type: 'POST', 
                data: option, 
                dataType: 'json', 
                beforeSend: function() {
                    $('#qr_image').attr('src', '')
                    _this.prop('disabled', true).html('<div><div style="color:#fff;font-size:16px;text-transform:uppercase;font-weight:bold">Đang tạo giao dịch...</div><div style="color:#fff;">Vui lòng chờ trong giây lát</div></div>')
                },
                success: function(res) {
                    // _this.remove()
                    if (!res.success) {
                        toastr.error(res.data?.message || 'Không thể khởi tạo giao dịch', 'Lỗi')
                        return
                    }
                    const data = res.data

                    let transactions = []
                    try {
                        transactions = JSON.parse(localStorage.getItem('pending_transactions') || '[]')
                    } catch {
                        transactions = []
                    }

                    // 🔍 Kiểm tra xem đã tồn tại giao dịch này chưa
                    const exists = transactions.some(tx => tx.transaction_code === data.transaction_code)

                    if (!exists) {
                        transactions.push({
                            id: data.id,
                            transaction_code: data.transaction_code,
                            created_at: Date.now(),
                            status: data.status || 'pending'
                        })
                        localStorage.setItem('pending_transactions', JSON.stringify(transactions))
                    }

                    const $qrImage = $('#qr_image')
                    $qrImage.attr('src', data.qr_image)

                    $qrImage.off('load').on('load', function () {
                        const qrModal = UIkit.modal('.qrcodeModal')
                        qrModal.show()
                    })
                    
                },
                complete: function() {
                    _this.prop('disabled', false).html(oldButton)
                }
            });
            e.preventDefault()
        })
    }

    HT.pollingTransactionCheck = () => {
        setInterval(() => {
            let pending = JSON.parse(localStorage.getItem('pending_transactions') || '[]')
            if (!pending.length) return

            const next = []
            const requests = pending.map(tx => $.get('ajax/transaction/status', { code: tx.transaction_code }))

            Promise.allSettled(requests).then(results => {
                results.forEach((r, i) => {
                    const res = r.value
                    if (res?.success && res.data?.status === 'pending') {
                        next.push(pending[i])
                    }
                })
                localStorage.setItem('pending_transactions', JSON.stringify(next))
            })
        }, 5000)
    }
    
    
    
    $(document).ready(function(){
        HT.highlightTocOnScroll();
        /* CORE JS */
        HT.swiper()
        HT.niceSelect()		
        HT.select2()
        HT.wrapTable()
        HT.skeleton()

        /** ACTION  */
        HT.chooseGarenaCard()
        HT.payCard()
        HT.buyAccount()
        HT.pollingTransactionCheck()

       

        $(document).on('hidden.uk.modal', '.qrcodeModal', function() {
        if (checkStatus) {
            clearInterval(checkStatus);
            checkStatus = null;
            lastStatus = null;
            console.log('🛑 Modal đóng → dừng polling');
        }
    });


    });


})(jQuery);
