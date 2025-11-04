<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail; 
use App\Models\Customer;
use App\Enums\SlideEnum;
use App\Http\Requests\VerifyEmailRequest;

// use App\Services\V1\Customer\CustomerService;
use App\Services\V1\Core\SlideService;
use App\Services\V2\Impl\Customer\CustomerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Http\Requests\ChangePasswordRequest;


class AuthController extends FrontendController
{
    protected $customerService;
    protected $slideService;
    public function __construct(
        CustomerService $customerService,
        SlideService $slideService,
    ){
        $this->customerService = $customerService;
        $this->slideService = $slideService;
        parent::__construct();
    }

    public function index(){
        $system = $this->system;
        $seo = [
            'meta_title' => 'Trang đăng nhập - Hệ thống website '.$this->system['homepage_company'],
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => 'dang-nhap.html'
        ];
        return view('frontend.auth.index', compact(
            'seo',
            'system',
        ));
    }

    public function login(AuthRequest $request){

        $loginInput = $request->input('email');
        $password = $request->input('password');
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'account';
        

        $credentials = [
            $fieldType => $loginInput,
            'password' => $password
        ];
        if(Auth::guard('customer')->attempt($credentials)){
            $user = Auth::guard('customer')->user();
            $request->session()->regenerate();
            return redirect()->intended(route('home.index'))->with('success', 'Đăng nhập thành công');
        }
        return redirect()->route('customer.auth')->with('error','Email hoặc Mật khẩu không chính xác');
    }


  
    public function register(){
        $seo = [
            'meta_title' => 'Trang đăng ký tài khoản hệ thống website',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => ''
        ];
        $system = $this->system;
        return view('frontend.auth.customer.register',compact(
            'seo',
            'system',
        ));
    }
    
    public function registerAccount(AuthRegisterRequest $request){
        if($this->customerService->save($request->merge(['customer_catalogue_id' => 1, '']))){
           return redirect()->route('customer.auth')->with('success','Đăng kí tài khoản thành công');
        }
        return redirect()->route('customer.register')->with('error','Thêm mới bản ghi không thành công. Hãy thử lại');
    }

    public function forgotPassword(){
        // dd(123);
        $seo = [
            'meta_title' => 'Quên mật khẩu',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.password.forgot')
        ];
        $route = '';
        $system = $this->system;
        return view('frontend.auth.components.forgotPassword',compact(
            'seo',
            'system',
            'route'
        ));
    }

    public function verifyCustomerEmail(VerifyEmailRequest $request){
        $emailReset = $request->input('email');
        $customer = Customer::where('email', $emailReset)->first();
        if(!is_null($customer)){
            $token = Str::random(64);
            DB::table('customer_password_resets')->updateOrInsert(
                ['email' => $emailReset],
                ['token' => $token, 'created_at' => now()]
            );

            $resetUrl = route('customer.update.password', ['token' => $token]);
            // dd($resetUrl);

            Mail::to($emailReset)->send(new ResetPasswordMail($emailReset));
            return redirect()->route('customer.auth')
            ->with('success','Gửi yêu cầu cập nhật mật khẩu thành công, vui lòng truy cập email của bạn để cập nhật mật khẩu mới');
        }
        return redirect()->route('customer.password.forgot')->with('success','Gửi yêu cầu cập nhật mật khẩu thành công, vui lòng truy cập email của bạn để cập nhật mật khẩu mới');
    }


    public function updatePassword($token){
        
        $reset = DB::table('customer_password_resets')->where('token', $token)->first();
        if (!$reset) {
            return redirect()->route('customer.auth')
                ->with('error', 'Liên kết không hợp lệ hoặc đã hết hạn. Vui lòng yêu cầu lại.');
        }
        $tokenLifetime = 60; // phút
        if (Carbon::parse($reset->created_at)->addMinutes($tokenLifetime)->isPast()) {
            // Xóa token hết hạn
            DB::table('customer_password_resets')->where('token', $token)->delete();

            return redirect()->route('customer.auth')
                ->with('error', 'Liên kết đặt lại mật khẩu đã hết hạn. Vui lòng gửi lại yêu cầu mới.');
        }

        $email = $reset->email;

        $seo = [
            'meta_title' => 'Thông tin kích hoạt bảo hành',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => route('customer.update.password', ['token' => $token])
        ];
        $system = $this->system;
        $route = 'customer.password.reset';
        return view('frontend.auth.components.updatePassword',compact(
            'system',
            'seo',
            'route',
            'email',
            'token'
        ));
    }
    
    public function changePassword(ChangePasswordRequest $request)
    {
        $email = $request->email;
        $token = $request->token;
        $password = $request->password;
         $reset = DB::table('customer_password_resets')
        ->where(['email' => $email, 'token' => $token])
        ->first();

        if (!$reset) {
            return redirect()->route('customer.auth')
                ->with('error', 'Liên kết không hợp lệ hoặc đã được sử dụng.');
        }

        // ⏳ Kiểm tra token hết hạn (60 phút)
        if (now()->diffInMinutes($reset->created_at) > 60) {
            DB::table('customer_password_resets')->where('email', $email)->delete();
            return redirect()->route('customer.auth')
                ->with('error', 'Liên kết đặt lại mật khẩu đã hết hạn. Vui lòng yêu cầu lại.');
        }

        // ✅ Cập nhật mật khẩu mới cho khách hàng
        $customer = Customer::where('email', $email)->first();
        if (!$customer) {
            return redirect()->route('customer.auth')
                ->with('error', 'Không tìm thấy tài khoản khách hàng.');
        }

        $customer->update([
            'password' => $password
        ]);

        // 🧹 Xóa token để không dùng lại
        DB::table('customer_password_resets')->where('email', $email)->delete();

        // ✅ Hoàn tất
        return redirect()->route('customer.auth')
            ->with('success', 'Mật khẩu đã được cập nhật thành công. Vui lòng đăng nhập lại.');
    }

    public function logout(Request $request)
    {
        // Đăng xuất người dùng khỏi guard 'customer'
        Auth::guard('customer')->logout();

        // Xoá session hiện tại và tạo lại token CSRF mới
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Chuyển hướng về trang chủ hoặc trang đăng nhập
        return redirect()->route('home.index')->with('success', 'Đăng xuất thành công!');
    }

  
}
