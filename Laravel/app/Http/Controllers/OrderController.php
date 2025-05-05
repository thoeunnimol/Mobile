<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.product'])
            ->search($request->search)
            ->filterByStatus($request->status)
            ->filterByPaymentStatus($request->payment_status)
            ->filterByDateRange($request->date_from, $request->date_to)
            ->latest();

        $orders = $query->paginate(10);

        return response()->json([
            'data' => $orders->items(),
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'total' => $orders->total(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Received order request:', $request->all());

            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'shipping_address' => 'required|string',
                'billing_address' => 'required|string',
                'order_date' => 'required|date',
                'total_amount' => 'required|numeric|min:0'
            ]);

            \Log::info('Validated order data:', $validated);

            DB::beginTransaction();

            try {
                // Generate order number
                $orderNumber = 'ORD-' . strtoupper(uniqid());

                $order = Order::create([
                    'customer_id' => $validated['customer_id'],
                    'order_number' => $orderNumber,
                    'shipping_address' => $validated['shipping_address'],
                    'billing_address' => $validated['billing_address'],
                    'order_date' => $validated['order_date'],
                    'total_amount' => $validated['total_amount'],
                    'payment_status' => 'pending'
                ]);

                \Log::info('Created order:', ['order_id' => $order->id]);

                foreach ($validated['items'] as $item) {
                    $orderItem = $order->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['quantity'] * $item['unit_price']
                    ]);

                    \Log::info('Created order item:', [
                        'order_id' => $order->id,
                        'order_item_id' => $orderItem->id
                    ]);
                }

                DB::commit();
                \Log::info('Order creation completed successfully', ['order_id' => $order->id]);

                return response()->json([
                    'message' => 'Order created successfully',
                    'order' => $order->load('items.product')
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error creating order:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Unexpected error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Order $order)
    {
        return response()->json([
            'order' => $order->load(['customer', 'items.product'])
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed'
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        return response()->json([
            'message' => 'Payment status updated successfully',
            'order' => $order
        ]);
    }

    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->items()->delete();
            $order->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function customerOrders(Request $request)
    {
        $customer = $request->user();
        
        $query = Order::with(['items.product'])
            ->where('customer_id', $customer->id)
            ->search($request->search)
            ->filterByStatus($request->status)
            ->filterByPaymentStatus($request->payment_status)
            ->filterByDateRange($request->date_from, $request->date_to)
            ->latest();

        $orders = $query->paginate(10);

        return response()->json([
            'data' => $orders->items(),
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'total' => $orders->total(),
        ]);
    }
} 