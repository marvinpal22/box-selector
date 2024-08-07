<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PackingService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreBoxRequest;

class BoxController extends Controller
{
    //
    public function __construct(
        private PackingService $packingServices,
    ) {}
    
    public function packItem(StoreBoxRequest $request){

        $products = $this->packingServices->findSmallestBoxWithProductsRemoval($request->product);
        return response()->json($products);

    }
}
