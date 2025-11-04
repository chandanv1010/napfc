<?php  
namespace App\Services\V2\Interfaces;
use Illuminate\Http\Request;


interface BaseServiceInterface {
    public function getCatalogueChildren($catalogue = null, $request);
    public function findById($id);
    public function pagination(Request $request);
    public function save(Request $request,  string $action = 'store', ?int $id = null);
    public function destroy($id);
    public function all(array $relation = [], string $selectRaw = '');
    public function convertDateSelectBox();
}