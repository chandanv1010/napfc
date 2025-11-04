<?php   
namespace App\Services\V2\Impl\Customer;

use App\Services\V2\BaseService;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Customer\CustomerRepository;

class CustomerService extends BaseService {

    protected $repository;
    protected $fillable;

    public function __construct(
        CustomerRepository $repository
    )
    {
        $this->repository = $repository;
    }
    
    public function prepareModelData(): static {
        $request = $this->context['request'] ?? null;
        if(!is_null($request)){
            $this->fillable = $this->repository->getFillable();
            $this->modelData = $request->only($this->fillable);
        }
        return $this;
    }

}