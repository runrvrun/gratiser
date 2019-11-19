<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadImagesDeleted;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

use App\Merchant;
use App\Store;
use App\Product;
use App\StoreProductStock;

class VoyagerProductStoreStocksController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    public function getStore(Request $request)
    {
        return Store::select('id','name')->where('merchant_id',$request->merchant_id)->get();
    }

    public function getProduct(Request $request)
    {
        return Product::select('id','name')->where('merchant_id',$request->merchant_id)->get();
    }
    
    public function store(Request $request)
    {
        // check if already there then update, not allowing double entry
        $storeproductstock = StoreProductStock::where('store_id',$request->store_id)->where('product_id',$request->product_id)->first();
        if(isset($storeproductstock->id))
        {
            // already exist, update
            $slug = $this->getSlug($request);

            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // Compatibility with Model binding.
            $id = $storeproductstock->id;

            $model = app($dataType->model_name);
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $model = $model->{$dataType->scope}();
            }
            if ($model && in_array(SoftDeletes::class, class_uses($model))) {
                $data = $model->withTrashed()->findOrFail($id);
            } else {
                $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
            }

            // Check permission
            $this->authorize('edit', $data);

            // Validate fields with ajax
            $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            event(new BreadDataUpdated($dataType, $data));

            return redirect()
            ->route("voyager.{$dataType->slug}.index")
            ->with([
                'message'    => __('voyager::generic.successfully_updated')." {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);
        }else{
            // new, insert
            $slug = $this->getSlug($request);
            
            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();
            
            // Check permission
            $this->authorize('add', app($dataType->model_name));
            
            // Validate fields with ajax
            $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());
        
        event(new BreadDataAdded($dataType, $data));
        
        return redirect()
        ->route("voyager.{$dataType->slug}.index")
        ->with([
            'message'    => __('voyager::generic.successfully_added_new')." {$dataType->display_name_singular}",
            'alert-type' => 'success',
            ]);
        }
    }
}