<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;

class ProductsController extends Controller
{
    public function productsList(Request $request){

        $products = Product::where('status', 1)->get();
        if(!$products){
            return $this->sendResponse($success, 'No products found!');
        }
        $success['products'] = $products;
        return $this->sendResponse($success, 'Products found!.');
    }
}
