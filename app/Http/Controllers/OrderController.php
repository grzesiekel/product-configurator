<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        //         $order = Order::find(9);
        // $cart = $order->cart;

        // dd($cart);
        // Walidacja danych wejściowych
        $validatedData = $request->validate([
            'cart' => 'required|json',
            // 'email' => 'required|email',
            // 'phone' => 'required|string',
            // 'totalRunningMeters' => 'required|numeric'
        ]);

        // Dekodowanie JSON cart do tablicy PHP
        $cartData = json_decode($validatedData['cart'], true);


        // Tworzenie nowego zamówienia
        $order = new Order();
        $order->cart = $cartData; // Laravel automatycznie zakoduje to z powrotem do JSON
        $order->product()->associate($request->product_id);

        $order->save();
        dd('sukces');


        return response()->json([
            'message' => 'Zamówienie zostało pomyślnie utworzone',
            'order_id' => $order->id
        ], 201);
    }

    public function edit(Order $order)
    {
        $product = $order->product;
        if (!$product->view) {
            abort(404);
        }

        $attributes = Attribute::all();

        return view('templates.' . $product->view . '.edit', compact('product', 'order', 'attributes'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'cart' => 'required|json',
        ]);

        $order->cart = json_decode($request->cart, true);
        $order->save();

        return back();
    }

    public function print(Order $order)
    {
        $cartData = $order->cart;

        return view('print', compact('cartData'));
    }
}
