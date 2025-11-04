<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;


use App\Repositories\Product\ProductCatalogueRepository;
use App\Services\V1\Product\ProductCatalogueService;
use App\Services\V1\Product\ProductService;
use App\Services\V1\Core\WidgetService;
use App\Repositories\Product\ProductRepository;


use Gloudemans\Shoppingcart\Facades\Cart;
use Jenssegers\Agent\Facades\Agent;

use App\Models\Post;

class ProductCatalogueController extends FrontendController
{
    protected $language;
    protected $system;
    protected $productCatalogueRepository;
    protected $productCatalogueService;
    protected $productService;
    protected $widgetService;
    protected $productRepository;

    public function __construct(
        ProductCatalogueRepository $productCatalogueRepository,
        ProductCatalogueService $productCatalogueService,
        ProductService $productService,
        ProductRepository $productRepository,
        WidgetService $widgetService,
    ) {
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->productCatalogueService = $productCatalogueService;
        $this->productService = $productService;
        $this->widgetService = $widgetService;
        $this->productRepository = $productRepository;
        parent::__construct();
    }


    public function index($id, $request, $page = 1)
    {

        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);

        $parent = null;

        $descendantTrees = null;

        $descendantTrees = $this->productCatalogueService->getChildren();

        $filters = $this->filter($productCatalogue);

        $breadcrumb = $this->productCatalogueRepository->breadcrumb($productCatalogue, $this->language);

        $products = $this->productService->paginate(
            $request,
            $this->language,
            $productCatalogue,
            $page,
            ['path' => $productCatalogue->canonical],
        );

        $products = $this->combineProductValues($products);


        $config = $this->config();

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'featured-products'],
            ['keyword' => 'product-category', 'children' => true],
            ['keyword' => 'product-category-highlight', 'object' => true],
            ['keyword' => 'about-us-2'],
        ], $this->language);

        

        $config = $this->config();
        $system = $this->system;
        $seo = seo($productCatalogue, $page);
        $schema = $this->schema($productCatalogue, $products, $breadcrumb);
        $template = 'frontend.product.catalogue.index';
        return view($template, compact(
            'descendantTrees',
            'config',
            'seo',
            'system',
            'breadcrumb',
            'productCatalogue',
            'products',
            'filters',
            'widgets',
            'schema',
        ));
    }

    private function combineProductValues($products)
    {
        $productId = $products->pluck('id')->toArray();
        if (count($productId) && !is_null($productId)) {
            $products = $this->productService->combineProductAndPromotion($productId, $products);
            // $products = $this->productService->combineProductRelation($products);
        }

        return $products;
    }

    private function filter($productCatalogue)
    {
        $filters = null;
        $children = $this->productCatalogueRepository->getChildren($productCatalogue);
        $groupedAttributes = [];
        foreach ($children as $child) {
            if (isset($child->attribute) && !is_null($child->attribute) && count($child->attribute)) {
                foreach ($child->attribute as $key => $value) {
                    if (!isset($groupedAttributes[$key])) {
                        $groupedAttributes[$key] = [];
                    }
                    $groupedAttributes[$key][] = $value;
                }
            }
        }
        foreach ($groupedAttributes as $key => $value) {
            $groupedAttributes[$key] = array_merge(...$value);
        }

        if (isset($groupedAttributes) && !is_null($groupedAttributes) && count($groupedAttributes)) {
            $filters = $this->productCatalogueService->getFilterList($groupedAttributes, $this->language);
        }
        return $filters;
    }


    public function search(Request $request)
    {

        $products = $this->productRepository->search($request->input('keyword'), $this->language);

        $productId = $products->pluck('id')->toArray();

        if (count($productId) && !is_null($productId)) {
            $products = $this->productService->combineProductAndPromotion($productId, $products);
        }

        $config = $this->config();

        $system = $this->system;

        $widgets = $this->widgetService->getWidget([
            ['keyword' => 'news-outstanding', 'object' => true],
        ], $this->language);

        $seo = [
            'meta_title' => 'Tìm kiếm cho từ khóa: ' . $request->input('keyword'),
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('tim-kiem')
        ];

        if (Agent::isMobile()) {
            $template = 'mobile.product.catalogue.search';
        } else {
            $template = 'frontend.product.catalogue.search';
        }


        return view($template, compact(
            'config',
            'seo',
            'system',
            'products',
            'widgets'
        ));
    }

    public function wishlist(Request $request)
    {
        $id = Cart::instance('wishlist')->content()->pluck('id')->toArray();
        $products = $this->productRepository->findByIds($id, $this->language);
        $productId = $products->pluck('id')->toArray();
        if (count($productId) && !is_null($productId)) {
            $products = $this->productService->combineProductAndPromotion($productId, $products);
        }

        $config = $this->config();
        $system = $this->system;
        $seo = [
            'meta_title' => 'Danh sách yêu thích',
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('tim-kiem')
        ];
        return view('frontend.product.catalogue.search', compact(
            'config',
            'seo',
            'system',
            'products',
        ));
    }

    private function schema($productCatalogue, $products, $breadcrumb)
    {

        $cat_name = $productCatalogue->languages->first()->pivot->name;

        $cat_canonical = write_url($productCatalogue->languages->first()->pivot->canonical);

        $cat_description = strip_tags($productCatalogue->languages->first()->pivot->description);

        $totalProducts = $products->total();

        $itemListElements = '';

        $position = 1;

        foreach ($products as $product) {
            $image = $product->image;
            $name = $product->languages->first()->pivot->name;
            $canonical = write_url($product->languages->first()->pivot->canonical);
            $itemListElements .= "
                {
                    \"@type\": \"ListItem\",
                    \"position\": $position,
                    \"item\": {
                        \"@type\": \"Product\",
                        \"name\": \"" . $name . "\",
                        \"url\": \"" . $canonical . "\",
                        \"image\": \"" . $image . "\"
                    }
                },";
            $position++;
        }

        $itemListElements = rtrim($itemListElements, ',');

        $itemBreadcrumbElements = '';

        $positionBreadcrumb = 2;

        foreach ($breadcrumb as $key => $item) {
            $name = $item->languages->first()->pivot->name;
            $canonical = write_url($item->languages->first()->pivot->canonical);
            $itemBreadcrumbElements .= "
                {
                    \"@type\": \"ListItem\",
                    \"position\": $positionBreadcrumb,
                    \"name\": \"" . $name . "\",
                    \"item\": \"" . $canonical . "\",
                },";
            $positionBreadcrumb++;
        }

        $itemBreadcrumbElements = rtrim($itemBreadcrumbElements, ',');

        $schema = "<script type='application/ld+json'>
            {
                \"@type\": \"BreadcrumbList\",
                \"itemListElement\": [
                    {
                        \"@type\": \"ListItem\",
                        \"position\": 1,
                        \"name\": \" Trang chủ  \",
                        \"item\": \" " . config('app.url') . " \"
                    },
                    $itemBreadcrumbElements
                ]
            },
            {
                \"@context\": \"https://schema.org\",
                \"@type\": \"CollectionPage\",
                \"name\": \"" . $cat_name . "\",
                \"description\": \" " . $cat_description . " \",
                \"url\": \"" . $cat_canonical . "\",
                \"mainEntity\": {
                    \"@type\": \"ItemList\",
                    \"name\": \" " . $cat_name . " \",
                    \"numberOfItems\": $totalProducts,
                    \"itemListElement\": [
                        $itemListElements
                    ]
                }
            }
            </script>";
        return $schema;
    }

    private function config()
    {
        return [
            'language' => $this->language,
            'externalJs' => [
                '//code.jquery.com/ui/1.11.4/jquery-ui.js'
            ],
            'css' => [
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/assets/owl.carousel.min.css',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/assets/owl.theme.default.min.css',
                'frontend/resources/css/custom.css',
            ],
            'js' => [
                'frontend/core/library/filter.js',
                'frontend/resources/plugins/OwlCarousel2-2.3.4/dist/owl.carousel.min.js',
                'frontend/resources/library/js/carousel.js',
            ],

        ];
    }

}