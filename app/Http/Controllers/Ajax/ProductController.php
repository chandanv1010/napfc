<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Services\V1\Product\ProductService;
use App\Repositories\Product\ProductCatalogueRepository;
use App\Repositories\Product\ProductVariantRepository;
use App\Repositories\Product\PromotionRepository;
use App\Repositories\Attribute\AttributeRepository;

use App\Models\Language;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
    protected $productService;
    protected $productCatalogueService;
    protected $productCatalogueRepository;
    protected $productVariantRepository;
    protected $promotionRepository;
    protected $attributeRepository;
    protected $language;

    public function __construct(
        ProductCatalogueRepository $productCatalogueRepository,
        ProductVariantRepository $productVariantRepository,
        PromotionRepository $promotionRepository,
        AttributeRepository $attributeRepository,
        ProductService $productService,
    ){
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->promotionRepository = $promotionRepository;
        $this->attributeRepository = $attributeRepository;
        $this->productService = $productService;
        $this->middleware(function($request, $next){
            $locale = app()->getLocale(); // vn en cn
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function loadProductPromotion(Request $request){

        $get = $request->input();

        $loadClass = loadClass($get['model']);
        
        if($get['model'] == 'Product'){
            $condition = [
                ['tb2.language_id', '=', $this->language]
            ];
            if(isset($get['keyword']) && $get['keyword'] != ''){
                $keywordCondition = ['tb2.name','LIKE', '%'.$get['keyword'].'%'];
                array_push($condition, $keywordCondition);
            }
            $objects = $loadClass->findProductForPromotion($condition);
        }else if($get['model'] == 'ProductCatalogue'){

            $conditionArray['keyword'] = ($get['keyword']) ?? null;
            $conditionArray['where'] = [
                ['tb2.language_id', '=', $this->language]
            ];

            $objects = $loadClass->pagination(
                [
                    'product_catalogues.id', 
                    'tb2.name', 
                ], 
                $conditionArray, 
                20,
                ['path' => 'product.catalogue.index'],  
                ['product_catalogues.id', 'DESC'],
                [
                    ['product_catalogue_language as tb2','tb2.product_catalogue_id', '=' , 'product_catalogues.id']
                ], 
                []
            );
        }

        return response()->json([
            'model' => ($get['model']) ?? 'Product' ,
            'objects' => $objects,
        ]);
    }

    public function loadProductVoucher(Request $request){

        $get = $request->input();

        $loadClass = loadClass($get['model']);

        $condition = [
            ['tb2.language_id', '=', $this->language]
        ];

        if(isset($get['keyword']) && $get['keyword'] != ''){
            $keywordCondition = ['tb2.name','LIKE', '%'.$get['keyword'].'%'];
            array_push($condition, $keywordCondition);
        }

        $objects = $loadClass->findProductForVoucher($condition);

        return response()->json([
            'model' => ($get['model']) ?? 'Product' ,
            'objects' => $objects,
        ]);
        
    }
   
    public function loadVariant(Request $request){
        $get = $request->input();
        $attributeId = $get['attribute_id'];
        
        $attributeId = sortAttributeId($attributeId);
        
        $variant = $this->productVariantRepository->findVariant($attributeId, $get['product_id'], $get['language_id']);

        $variantPromotion = $this->promotionRepository->findPromotionByVariantUuid($variant->uuid);
        $variantPrice = getVariantPrice($variant, $variantPromotion);

        return response()->json([
            'variant' => $variant ,
            'variantPrice' => $variantPrice,
        ]);
        
    }
    

    public function filter(Request $request){

        $products = $this->productService->filter($request);

        $countProduct = $products->count();

        $html = $this->renderFilterProduct($products);

        return response()->json([
            'data' => $html ,
            'countProduct' => $countProduct
        ]);
    }

    public function renderFilterProduct($products){
        $html = '';
        if (!is_null($products) && count($products)) {
            $html .= '<div class="uk-grid uk-grid-medium">';
            foreach ($products as $product) {
                $name = $product->languages->first()->pivot->name;
                $canonical = write_url($product->languages->first()->pivot->canonical);
                $image = image($product->image);
                $price = getPrice($product);
                $catName = $product->product_catalogues->first()->languages->first()->pivot->name;
                $review = getReview($product);
                $total_lesson = $product->total_lesson;
                $duration = $product->duration; 
                $review['star'] = ($product->review_count == 0) ? '0' : $product->review_average / 5 * 100;
                $lecturer_image = $product->lecturers->image;
                $lecturer_name = $product->lecturers->name;

                if (isset($product->attribute_concat)) {
                    $attributes = substr($product->attribute_concat, 0, -1);
                }

                $html .= <<<HTML
                    <div class="uk-width-large-1-3 mb20">
                        <div class="product-item">
                            <a href="{$canonical}" title="{$name}" class="image img-cover img-zoomin">
                                <!-- <div class="skeleton-loading"></div> -->
                                <img  src="{$image}" alt="{$name}">
                            </a>
                            <div class="info">
                                <div class="course">
                                    <div class="uk-flex uk-flex-middle">
                                        <div class="total-lesson uk-flex">
                                            <img src="/backend/img/lesson.svg" alt="">
                                            <span>{$total_lesson} Bài học</span>
                                        </div>
                                        <div class="duration uk-flex">
                                            <img src="/backend/img/time.svg" alt="">
                                            <span>{$duration}</span>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="title">
                                    <a href="{$canonical}" title="{$name}">{$name}</a>
                                </h3>
                                <div class="product-price">
                                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                        {$price['html']}
                                    </div>
                                </div>
                                <div class="info-lecturer">
                                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                        <div class="if">
                                            <a href="" class="image img-cover">
                                                <img src="{$lecturer_image}" alt="{$lecturer_name}">
                                            </a>
                                            <div class="text">
                                                <h4 class="heading-3"><span>{$lecturer_name}</span></h4>
                                                <div class="rating">
                                                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                                        <div>
                                                            <div class="star-rating">
                                                                <div class="stars" style="--star-width: {$review['star']}%"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{$canonical}" class="btn-detail">
                                            <span>Xem chi tiết</span>
                                            <svg width="4" height="6" viewBox="0 0 4 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3.49014 2.59539L1.44887 0.169256C1.40412 0.115625 1.35087 0.073056 1.2922 0.0440061C1.23354 0.0149562 1.17061 0 1.10706 0C1.0435 0 0.980576 0.0149562 0.921909 0.0440061C0.863242 0.073056 0.809996 0.115625 0.765241 0.169256C0.675574 0.276465 0.625244 0.421491 0.625244 0.572658C0.625244 0.723826 0.675574 0.868851 0.765241 0.97606L2.4695 3.00165L0.765241 5.02725C0.675574 5.13446 0.625244 5.27948 0.625244 5.43065C0.625244 5.58182 0.675574 5.72684 0.765241 5.83405C0.810225 5.88708 0.863576 5.92904 0.922232 5.95752C0.980888 5.98599 1.0437 6.00043 1.10706 5.99999C1.17042 6.00043 1.23322 5.98599 1.29188 5.95752C1.35054 5.92904 1.40389 5.88708 1.44887 5.83405L3.49014 3.40792C3.53526 3.35472 3.57108 3.29144 3.59552 3.22171C3.61996 3.15198 3.63254 3.07719 3.63254 3.00165C3.63254 2.92612 3.61996 2.85133 3.59552 2.7816C3.57108 2.71187 3.53526 2.64858 3.49014 2.59539Z" fill="#F2277E"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                HTML;
            }
            $html .= '</div>';
            $html .= $products->links('pagination::bootstrap-4');
        } else {
            $html .= '<div class="no-result">Không có sản phẩm phù hợp</div>';
        }
        return $html;
    }

  

    public function wishlist(Request $request){
        $id = $request->input('id');
        $wishlist = Cart::instance('wishlist')->content();
        $itemIndex = $wishlist->search(function ($item, $rowId) use ($id) {
            return $item->id === $id;
        });
        
        $response['code'] = 0;
        $response['message'] = '';
        if ($itemIndex !== false) {
            Cart::instance('wishlist')->remove($wishlist->keyBy('id')[$id]->rowId);

            $response['code'] = 1;
            $response['message'] = 'Sản phẩm đã được xóa khỏi danh sách yêu thích';

        } else {
            Cart::instance('wishlist')->add([
                'id' => $id,
                'name' => 'wishlist item',
                'qty' => 1,
                'price' => 0,
            ]);

            $response['code'] = 2;
            $response['message'] = 'Đã thêm sản phẩm vào danh sách yêu thích';
        }

        return response()->json($response);
    }

    public function updateOrder(Request $request){
        $payload['order'] =  $request->input('order');
        unset($payload['id']);
        $id = $request->input('id');
        $class = loadClass($request->input('model'));
        $update_order = $class->update($id, $payload);
        return response()->json([
            'response' => $update_order, 
            'messages' => 'Cập nhật thứ tự thành công',
            'code' => (!$update_order) ? 11 : 10,
        ]);  
    }


    public function createTransaction(Request $request){
        try {
            DB::beginTransaction();

            $account = $request->input('account');
            $productId = $request->input('id');
            $amount = $request->input('amount');
            $customerId = $request->input('customerId');
            if (!$account || !$productId || !$amount) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'status' => 'invalid',
                        'message' => 'Thiếu thông tin cần thiết (account, sản phẩm, hoặc số tiền).'
                    ]
                ]);
            }

            $existing = Transaction::where('product_id', $productId)
                ->where('account', $account)
                ->where('type', 'garena')
                ->where('status', 'pending')
                ->first();

            $bankBin       = '970416';
            $accountNumber = '336883868386';
            $accountName   = 'Bui Phuong Dai';

            if ($existing) {
                $transactionCode = $existing->transaction_code;
            } else {
                // Nếu chưa tồn tại → tạo giao dịch mới
                $transactionCode = 'SHOPFC' . str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);

                $existing = Transaction::create([
                    'transaction_code' => $transactionCode,
                    'product_id'       => $productId,
                    'account'          => $account,
                    'amount'           => $amount,
                    'customer_id'      => $customerId,
                    'status'           => 'pending',
                    'type'             => 'garena',
                ]);
            }

            $qrLink = sprintf(
                'https://img.vietqr.io/image/%s-%s-print.png?amount=%d&addInfo=%s&accountName=%s',
                $bankBin,
                $accountNumber,
                $existing->amount,
                $transactionCode,
                urlencode($accountName)
            );
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $existing->id,
                    'transaction_code' => $transactionCode,
                    'qr_image'         => $qrLink,
                    'status'           => $existing->status,
                ]
            ]);

           
        } catch (\Throwable $th) {
            Log::error('❌ Lỗi tạo giao dịch: '.$th->getMessage(), [
                'trace' => $th->getTraceAsString()
            ]);
            DB::rollBack();
            return response()->json([
                'success' => false,
                'data' => [
                    'status'  => 'error',
                    'message' => 'Đã xảy ra lỗi khi tạo giao dịch, vui lòng thử lại.'
                ]
            ]);
        }
    }

    public function checkTransactionStatus(Request $request){
        try {

            $transactionCode = $request->input('code');
            if(!$transactionCode){
                return response()->json([
                    'success' => false,
                    'data' => [
                        'status' => 'Invalid',
                        'message' => 'Transaction Code không hợp lệ'
                    ]
                ]);
            }

            $transaction = Transaction::where('transaction_code', $transactionCode)->first();
            if(!$transaction){
                return response()->json([
                    'success' => false,
                    'data' => [
                        'status' => 'not_found',
                        'message' => 'Transaction không hợp lệ'
                    ]
                ]);
            }

            if ($transaction->status === 'pending') {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'status'  => 'pending',
                        'message' => 'Giao dịch chưa được hoàn tất.',
                        // 'transaction_code' => $transaction->transaction_code,
                        // 'amount' => $transaction->amount,
                        // 'account' => $transaction->account,
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'status'  => 'success',
                    'message' => 'Giao dịch đã được xử lý.',
                    // 'transaction_code' => $transaction->transaction_code,
                    // 'amount' => $transaction->amount,
                    // 'account' => $transaction->account,
                ]
            ]);
            
        } catch (\Throwable $th) {
             Log::error('❌ Lỗi kiểm tra trạng thái giao dịch: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'data' => [
                    'status'  => 'error',
                    'message' => 'Đã xảy ra lỗi trong quá trình kiểm tra giao dịch.'
                ]
            ]);
        }
    }

    public function createAccountTransaction(Request $request){
        if(!auth('customer')->check()){
            return response()->json([
                'success' => false,
                'data' => [
                    'status' => 'unauthorized',
                    'message' => 'Chưa đăng nhập'
                ]
            ]);
        }



        $product = Product::findOrFail($request->id);
        $customer = auth('customer')->user();
        try {
            DB::beginTransaction();
            $amount = $product->price;


            Transaction::where('product_id', $product->id)
                ->where('customer_id', $customer->id)
                ->where('type', 'account')
                ->where('status', 'pending')
                ->where('amount', '!=', $amount)
                ->update([
                    'status' => 'expired',
                    'description' => 'Hết hiệu lực do thay đổi giá sản phẩm',
                    'updated_at' => now(),
                ]);

             $existing = Transaction::where('product_id', $product->id)
                ->where('customer_id', $customer->id)
                ->where('type', 'account')
                ->where('status', 'pending')
                ->where('amount', $product->price)
                ->where('created_at', '>=', now()->subMinutes(10))
                ->lockForUpdate()
                ->first();

            $bankBin       = '970416';
            $accountNumber = '336883868386';
            $accountName   = 'Bui Phuong Dai';

            if ($existing) {
                $transaction = $existing;
            } else {
                // ✅ 3. Tạo giao dịch mới
                $transactionCode = 'SHOPACC' . str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);


                try {
                    $transaction = Transaction::create([
                        'transaction_code' => $transactionCode,
                        'product_id' => $product->id,
                        'customer_id' => $customer->id,
                        'amount' => $amount,
                        'status' => 'pending',
                        'type' => 'account',
                    ]);
                } catch (QueryException $e) {
                    // Nếu bị duplicate key (do unique constraint)
                    if (str_contains($e->getMessage(), 'Duplicate entry')) {
                        DB::rollBack();
                        $transaction = Transaction::where('product_id', $product->id)
                            ->where('customer_id', $customer->id)
                            ->where('type', 'account')
                            ->where('status', 'pending')
                            ->latest('id')
                            ->first();
                    } else {
                        throw $e;
                    }
                }
            }

            $qrLink = sprintf(
                'https://img.vietqr.io/image/%s-%s-print.png?amount=%d&addInfo=%s&accountName=%s',
                $bankBin,
                $accountNumber,
                $transaction->amount,
                $transaction->transaction_code,
                urlencode($accountName)
            );

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'qr_image' => $qrLink,
                    'status' => $transaction->status,
                ]
            ]);



        } catch (\Throwable $th) {
            Log::error('❌ Lỗi kiểm tra trạng thái giao dịch: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'data' => [
                    'status'  => 'error',
                    'message' => 'Đã xảy ra lỗi trong quá trình kiểm tra giao dịch.'
                ]
            ]);
        }
    }

    public function accountTransactionStatus($code)
    {
        // 🔍 Tìm giao dịch theo transaction_code
        $transaction = Transaction::where('transaction_code', $code)->first();


        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy giao dịch này.',
            ], 404);
        }

        // 🟡 Nếu vẫn đang pending → tiếp tục polling
        if ($transaction->status === 'pending') {
            return response()->json([
                'success' => true,
                'status' => 'pending',
                'message' => 'Giao dịch đang chờ thanh toán.',
            ]);
        }

        // ✅ Nếu đã thanh toán
        if ($transaction->status === 'paid') {
            $product = Product::find($transaction->product_id);

            // Tránh lỗi nếu sản phẩm đã bị xóa hoặc thiếu dữ liệu
            $accountInfo = $product->account_info ?? 'Thông tin tài khoản chưa được cập nhật.';
            $productName = $product->title ?? 'Sản phẩm không xác định';

            return response()->json([
                'success' => true,
                'status' => 'paid',
                'transaction_code' => $transaction->transaction_code,
                'product_name' => $productName,
                'account_info' => $accountInfo,
                'paid_at' => $transaction->paid_at
                    ? \Carbon\Carbon::parse($transaction->paid_at)->format('d/m/Y H:i:s')
                    : null,
                        ]);
                    }

        // 🔴 Nếu transaction bị lỗi hoặc invalid
        return response()->json([
            'success' => false,
            'status' => 'invalid',
            'message' => 'Giao dịch không hợp lệ hoặc đã bị hủy.',
        ]);
    }
    
}
