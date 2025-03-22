<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Support\Carbon;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Invoice::latest()->get();;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|string',
            'items' => 'required|string',
            'payment_method' => 'required|string',
            'price' => 'required|numeric',
            'profit' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'total' => 'required|numeric',
            'note' => 'nullable|string',
            'table_id' => 'required|integer|exists:tables,id',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        $items = json_decode($request->items, true);

        $profit = 0;
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            $product->quantity -= $item['qty'];
            $product->save();

            $profit += $product->profit * $item['qty'];
        }

        $invoiceData = $request->all();
        $invoiceData['profit'] = $profit;

        return Invoice::create($invoiceData);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Invoice::all();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->noContent();
    }

    public function statistical (Request $request) {
        $date = $request->date;
        $invoices = Invoice::whereDate('created_at', $date)->get();
        $sale = 0;
        $profit = 0;
        $count = 0;
        foreach ($invoices as $invoice) {
            $count++;
            $items = json_decode($invoice->items, true);
            foreach($items as $item) {
                $product = Product::find($item['id']);
                $sale += $product->price * $item['qty'];
                $profit += $product->profit * $item['qty'];
            }
        }

        $data = [
            'sale' => $sale,
            'profit' => $profit,
            'count' => $count,
        ];
        return response()->json($data);
    }
}
