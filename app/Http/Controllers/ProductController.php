<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Product $product)
{
    if (!$product->view) {
        abort(404);
    }
    
    return view('templates.'.$product->view.'.index', compact('product'));
}

public function edit(Order $order)
{
    $product = $order->product;
    if (!$product->view) {
        abort(404);
    }
    
    return view('templates.'.$product->view.'.edit', compact('product','order'));
}
}
