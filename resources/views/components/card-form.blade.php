@props(['scholars'])


<div class="panel-form">
    <div class="form-area">
        <div class="apply-form-4__bg__1 d-none d-xl-block"></div>
        <div class="apply-form-4__bg__2"></div>
        <div class="apply-form-4__thumb__bg" style="background-image: url(https://wp.rrdevs.net/routex/wp-content/uploads/2024/10/thumb-bg.jpg);"></div>
        <div class="apply-form-4__shape upDown d-none d-xxl-block" bis_skin_checked="1">
            <img decoding="async" src="/vendor/frontend/img/project/apply-form-4-shape.png" alt="image not found">
        </div>
        <div class="uk-container uk-container-center">
            <div class="uk-grid">
                <div class="uk-width-large-1-2">
                    <div class="apply-form-wrapper">
                        <form action="post" class="reg-form">
                            <div class="form-heading">
                                <div class="text-whit">Tài Khoản</div>
                                <h2 class="heading-3"><span>Đăng ký tài khoản để nhận ngay <br /> các chương trình <span class="clr">Ưu đãi</span> Mới nhất </span></h2>
                                <div class="description">
                                    Nhập đầy đủ tất cả các thông tin trong biểu mẫu dưới dây
                                </div>
                                <div class="form-container">
                                    <div class="uk-grid uk-grid-medium mb20">
                                        <div class="uk-width-large-1-2">
                                            <div class="form-row">
                                                <input class="input-text form-control" value="" required placeholder="Họ Tên *" type="text">
                                            </div>
                                        </div>
                                        <div class="uk-width-large-1-2">
                                            <div class="form-row">
                                                <input class="input-text form-control" value="" required placeholder="Email *" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-grid uk-grid-medium mb20">
                                        <div class="uk-width-large-1-2">
                                            <div class="form-row">
                                                <input class="input-text form-control" value="" required placeholder="Số điện thoại *" type="text">
                                            </div>
                                        </div>
                                        <div class="uk-width-large-1-2">
                                            <div class="form-row">
                                                <select name="schoolarshipType" id="schoolarshipType" class="form-control">
                                                    <option value="0">Chọn loại học bổng</option>
                                                    @forelse($scholars as $scholar)
                                                        <option value="{{ $scholar->id }}">{{ $scholar->languages->first()->pivot->name }}</option>
                                                    @empty
                                                        <option value="">Không có học bổng</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="uk-grid uk-grid-medium mb20">
                                        <div class="uk-width-large-1-1">
                                            <div class="form-row">
                                                <input class="input-text form-control" value="" placeholder="Địa chỉ *" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" name="submit" value="submit">Đăng Ký Ngay</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>