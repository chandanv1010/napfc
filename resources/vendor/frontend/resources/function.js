// const UIkit = require("uikit");

(function ($) {

    "use strict";
    var HT = {}; // Khai báo là 1 đối tượng
    var timer;
    var $carousel = $(".owl-slide");
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.swiperOption = (setting) => {
        // console.log(setting);
        let option = {}
        if (setting.animation.length) {
            option.effect = setting.animation;
        }
        if (setting.arrow === 'accept') {
            option.navigation = {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            }
        }
        if (setting.autoplay === 'accept') {
            option.autoplay = {
                delay: 50000,
                disableOnInteraction: false,
            }
        }
        if (setting.navigate === 'dots') {
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
                delay: 3000,
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
                delay: 2000,
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
        if ($('.nice-select').length) {
            $('.nice-select').niceSelect();
        }

    }

    HT.select2 = () => {
        if ($('.setupSelect2').length) {
            $('.setupSelect2').select2();
        }

    }


    HT.skeleton = () => {

        document.addEventListener("DOMContentLoaded", function () {
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
                        newImg.onload = function () {
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
        $('.filter-content').on('slide', function () {
            $('.uk-flex .pagination').hide();
        });
    };


    HT.wrapTable = () => {
        var width = $(window).width()
        if (width < 600) {
            $('table').wrap('<div class="uk-overflow-container"></div>')
        }
    }


    HT.advise = () => {
        $(document).on('click', '.suggest-aj button', function (e) {
            e.preventDefault()
            let _this = $(this)
            let option = {
                name: $('#suggest input[name=name]').val(),
                gender: $('#suggest input[name=gender]').val(),
                phone: $('#suggest input[name=phone]').val(),
                address: $('#suggest input[name=address]').val(),
                post_id: $('#suggest input[name=post_id ]').val(),
                product_id: $('#suggest input[name=product_id ]').val(),
                _token: _token,
            }
            toastr.success('Gửi yêu cầu thành công , chúng tôi sẽ sớm liên hệ vs bạn !', 'Thông báo từ hệ thống')
            $.ajax({
                url: 'ajax/contact/advise',
                type: 'POST',
                data: option,
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (res) {
                    console.log(res)
                    if (res.code === 10) {

                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else if (res.status === 422) {
                        let errors = res.messages;
                        for (let field in errors) {
                            let errorMessage = errors[field];
                            $('.' + field + '-error').text(errorMessage);
                        }
                    }
                },
            });

        })
    }

    HT.highlightTocOnScroll = () => {
        $(window).on('scroll', function () {
            let scrollTop = $(window).scrollTop();

            $('.widget-toc a').each(function () {
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

    HT.changeCardQuantity = () => {
        $(document).on('change', '#card-quantity', function () {
            const textQuantity = $('#text-quantity')
            const quantity = $(this).val()
            textQuantity.html(quantity)
            $('#do-card').attr('data-quantity', quantity)
        })
    }

    HT.chooseGarenaCard = () => {
        // Utility functions
        function formatNumber(num) {
            return new Intl.NumberFormat('vi-VN').format(num);
        }

        function showStep(sidebar, stepIndex) {
            const $sidebar = $(sidebar);
            $sidebar.find('.checkout-step').removeClass('active');
            $sidebar.find('.step-' + stepIndex).addClass('active');

            if (stepIndex > 0) {
                $sidebar.addClass('is-checkout');
            } else {
                $sidebar.removeClass('is-checkout');
            }
        }

        function updateStepData(sidebar, product, quantity) {
            const $sidebar = $(sidebar);
            const price = parseFloat(product.price);
            const totalPrice = price * quantity;
            const name = product.languages[0]?.name ?? 'Thẻ Garena';

            $sidebar.find('.total-price-val').each(function() {
                const suffix = $(this).hasClass('text-yellow') ? '' : ' ₫';
                $(this).text(formatNumber(totalPrice) + suffix);
            });
            $sidebar.find('.product-name-val').text(name);
            $sidebar.find('.unit-price-val').text(formatNumber(price) + ' đ');
            $sidebar.find('.quantity-val').text(quantity);

            // Store product data on sidebar for later use
            $sidebar.data('currentProduct', product);
            $sidebar.data('currentQuantity', quantity);
        }

        // 1. Card click → Show Step 1 (Consent)
        $(document).on('click', '.garena-item', function () {
            const _this = $(this);
            const panel = _this.closest('.panel-garena');
            const sidebar = panel.find('.garena-checkout-sidebar');
            const $quantity = panel.find('#card-quantity');

            // Reset quantity
            $quantity.val(1);

            // Clear other selections
            panel.find('.garena-item').removeClass('active');
            _this.addClass('active');

            const product = JSON.parse(_this.attr('data-card'));
            const quantity = parseInt($quantity.val()) || 1;

            updateStepData(sidebar, product, quantity);
            showStep(sidebar, 1); // Show consent form (Ảnh 1)
        });

        // 2. Step 1 → Step 2: "TIẾP TỤC" button (after consent agree)
        $(document).on('click', '.btn-next-step-1', function () {
            const sidebar = $(this).closest('.garena-checkout-sidebar');
            const consentAgree = sidebar.find('#consent-agree');
            const errorMsg = sidebar.find('.error-msg-consent');

            if (consentAgree.is(':checked')) {
                errorMsg.addClass('uk-hidden');
                showStep(sidebar, 2); // Show order details (Ảnh 2)
            } else {
                errorMsg.removeClass('uk-hidden');
            }
        });

        // 3. Step 2 → Step 3: "THANH TOÁN NGAY" button
        $(document).on('click', '.btn-pay-now', function (e) {
            e.preventDefault();
            const sidebar = $(this).closest('.garena-checkout-sidebar');
            const targetAccount = sidebar.find('#target-account');

            if (!targetAccount.val() || targetAccount.val().trim() === '') {
                alert('Vui lòng nhập tài khoản cần nạp!');
                targetAccount.focus();
                return;
            }

            // Get stored product data
            const product = sidebar.data('currentProduct');
            const quantity = sidebar.data('currentQuantity') || 1;
            const price = parseFloat(product.price);
            const account = targetAccount.val().trim().toLowerCase();

            const _this = $(this);
            const oldHtml = _this.html();

            // Call API to create transaction
            $.ajax({
                url: 'ajax/transaction/create',
                type: 'POST',
                data: {
                    id: product.id,
                    _token: _token,
                    account: account,
                    amount: price,
                    customerId: window.customerId,
                    quantity: quantity
                },
                dataType: 'json',
                beforeSend: function () {
                    _this.prop('disabled', true).html(
                        '<span class="main">Đang tạo giao dịch...</span>' +
                        '<span class="sub">Vui lòng chờ trong giây lát</span>'
                    );
                },
                success: function (res) {
                    if (!res.success) {
                        toastr.error(res.data?.message || 'Không thể khởi tạo giao dịch', 'Lỗi');
                        _this.prop('disabled', false).html(oldHtml);
                        return;
                    }

                    const data = res.data;

                    // Save to localStorage
                    let transactions = [];
                    try {
                        transactions = JSON.parse(localStorage.getItem('pending_transactions') || '[]');
                    } catch { transactions = []; }

                    const exists = transactions.some(tx => tx.transaction_code === data.transaction_code);
                    if (!exists) {
                        transactions.push({
                            id: data.id,
                            transaction_code: data.transaction_code,
                            created_at: Date.now(),
                            status: data.status || 'pending'
                        });
                        localStorage.setItem('pending_transactions', JSON.stringify(transactions));
                    }

                    // Update Step 3 QR image in sidebar and modal
                    const $qrImg = sidebar.find('.step-3 .qr-image img');
                    const $modalQrImg = $('#order-payment-modal .qr-image img');
                    if (data.qr_image) {
                        $qrImg.attr('src', data.qr_image);
                        $modalQrImg.attr('src', data.qr_image);
                    }

                    // Update Step 3 transfer content if transaction code exists
                    if (data.transaction_code) {
                        sidebar.find('.step-3 .transfer-content-val').text(data.transaction_code);
                        $('#order-payment-modal .transfer-content-val').text(data.transaction_code);
                    }
                    if (data.amount) {
                        sidebar.find('.step-3 .transfer-amount-val').text(formatNumber(data.amount));
                        $('#order-payment-modal .transfer-amount-val').text(formatNumber(data.amount));
                    }

                    // Show Step 3 (Payment info - Ảnh 3)
                    showStep(sidebar, 3);
                },
                error: function () {
                    toastr.error('Có lỗi xảy ra, vui lòng thử lại!', 'Lỗi');
                    _this.prop('disabled', false).html(oldHtml);
                }
            });
        });

        // 4. "HỦY BỎ" button → back to Step 0
        $(document).on('click', '.btn-cancel', function () {
            const panel = $(this).closest('.panel-garena');
            const sidebar = panel.find('.garena-checkout-sidebar');
            showStep(sidebar, 0);
            panel.find('.garena-item').removeClass('active');
            // Reset consent checkbox
            sidebar.find('#consent-agree').prop('checked', false);
            sidebar.find('.error-msg-consent').addClass('uk-hidden');
        });

        // 5. Copy button
        $(document).on('click', '.btn-copy', function () {
            const text = $(this).parent().clone().children('.btn-copy').remove().end().text().trim();
            navigator.clipboard.writeText(text).then(() => {
                toastr.success('Đã sao chép: ' + text);
            });
        });

        // 6. Sync quantity changes
        $(document).on('change', '#card-quantity', function () {
            const panel = $(this).closest('.panel-garena');
            const activeItem = panel.find('.garena-item.active');
            if (activeItem.length) {
                const sidebar = panel.find('.garena-checkout-sidebar');
                const product = JSON.parse(activeItem.attr('data-card'));
                const quantity = parseInt($(this).val()) || 1;
                updateStepData(sidebar, product, quantity);
            }
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
                        const maxWait = Infinity; // 10 phút (tính bằng giây)

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

                                        // Tắt modal QR code
                                        modal.hide();

                                        // Hiển thị thông tin tài khoản trong modal (chỉ mở 1 lần cho mỗi transaction)
                                        if (resp.account_info) {
                                            const transactionCode = data.transaction_code;
                                            const shownKey = 'accountInfoShown_' + transactionCode;

                                            // Kiểm tra xem đã mở modal cho transaction này chưa
                                            if (!sessionStorage.getItem(shownKey)) {
                                                $('#account_info_text').text(resp.account_info);
                                                const accountModal = UIkit.modal('.accountInfoModal');
                                                accountModal.show();

                                                // Đánh dấu đã mở modal cho transaction này
                                                sessionStorage.setItem(shownKey, 'true');
                                            }
                                        }

                                        toastr.success('Thanh toán thành công!');
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
        $(document).off('click', '.btn-pay').on('click', '.btn-pay', function (e) {
            // alert('Chức năng sẽ sớm hoạt động...'); return false;
            let _this = $(this)
            var qrCodeModal = UIkit.modal(".qrcodeModal");

            const oldButton = _this.html()

            const id = _this.attr('data-id')
            const amount = parseFloat(_this.attr('data-price'))
            const account = $('#account-input').val()?.trim().toLowerCase()
            const quantity = _this.attr('data-quantity')
            const timestamp = Date.now()
            const customerId = window.customerId

            if (!account) {
                alert('Bạn chưa nhập vào account muốn nạp')
                return;
            }

            let option = {
                id,
                _token,
                account,
                amount,
                customerId,
                quantity
            }


            // toastr.success('Gửi yêu cầu thành công , chúng tôi sẽ sớm liên hệ vs bạn !', 'Thông báo từ hệ thống')
            $.ajax({
                url: 'ajax/transaction/create',
                type: 'POST',
                data: option,
                dataType: 'json',
                beforeSend: function () {
                    $('#qr_image').attr('src', '')
                    _this.prop('disabled', true).html('<div><div style="color:#fff;font-size:16px;text-transform:uppercase;font-weight:bold">Đang tạo giao dịch...</div><div style="color:#fff;">Vui lòng chờ trong giây lát</div></div>')
                },
                success: function (res) {
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
                complete: function () {
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
            const qrModal = UIkit.modal('.qrcodeModal')

            const next = []
            const requests = pending.map(tx => $.get('ajax/transaction/status', { code: tx.transaction_code }))

            Promise.allSettled(requests).then(results => {
                results.forEach((r, i) => {
                    const res = r.value
                    if (res?.success && res.data?.status === 'pending') {
                        next.push(pending[i])
                    } else if (res.success && res.data?.status === 'success') {
                        toastr.success('Giao dịch hoàn tất, hệ thống sẽ đóng QR.');
                        qrModal.hide();
                    }
                })
                localStorage.setItem('pending_transactions', JSON.stringify(next))
            })



        }, 5000)
    }



    $(document).ready(function () {
        HT.highlightTocOnScroll();
        /* CORE JS */
        HT.swiper()
        HT.niceSelect()
        HT.select2()
        HT.wrapTable()
        HT.skeleton()

        HT.changeCardQuantity()

        /** ACTION  */
        HT.chooseGarenaCard()
        HT.payCard()
        HT.buyAccount()
        HT.pollingTransactionCheck()



        $(document).on('hidden.uk.modal', '.qrcodeModal', function () {
            if (checkStatus) {
                clearInterval(checkStatus);
                checkStatus = null;
                lastStatus = null;
                console.log('🛑 Modal đóng → dừng polling');
            }
        });

        // Không cần reset flag vì đã dùng sessionStorage với transaction_code


    });


})(jQuery);
