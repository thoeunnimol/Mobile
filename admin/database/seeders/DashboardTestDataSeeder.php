<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class DashboardTestDataSeeder extends Seeder
{
    public function run()
    {
        // Create categories
        $categories = [
            'Electronics' => Category::create(['name' => 'Electronics']),
            'Clothing' => Category::create(['name' => 'Clothing']),
            'Books' => Category::create(['name' => 'Books']),
            'Home & Kitchen' => Category::create(['name' => 'Home & Kitchen']),
        ];

        // Create products
        $products = [];
        foreach ($categories as $categoryName => $category) {
            for ($i = 1; $i <= 3; $i++) {
                $products[] = Product::create([
                    'name' => "{$categoryName} Product {$i}",
                    'description' => "Description for {$categoryName} Product {$i}",
                    'price' => rand(10, 100),
                    'stock' => rand(10, 100),
                    'category_id' => $category->id,
                    'brand' => 'Test Brand',
                    'status' => 'published',
                    'is_active' => true,
                ]);
            }
        }

        // Create customers
        $customers = [];
        for ($i = 1; $i <= 10; $i++) {
            $customers[] = Customer::create([
                'name' => "Customer {$i}",
                'email' => "customer{$i}@example.com",
                'phone' => "1234567890",
                'address' => "Address for Customer {$i}",
                'is_active' => true,
            ]);
        }

        // Create orders
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];
        
        for ($i = 1; $i <= 20; $i++) {
            $customer = $customers[array_rand($customers)];
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            
            // Create order with random date in the last 30 days
            $orderDate = Carbon::now()->subDays(rand(0, 30));
            
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(substr(md5(uniqid()), 0, 12)),
                'customer_id' => $customer->id,
                'total_amount' => 0, // Will be updated after adding items
                'status' => $status,
                'payment_status' => $paymentStatus,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);
            
            // Add 1-5 items to the order
            $totalAmount = 0;
            $numItems = rand(1, 5);
            
            for ($j = 1; $j <= $numItems; $j++) {
                $product = $products[array_rand($products)];
                $quantity = rand(1, 3);
                $unitPrice = $product->price;
                $itemTotal = $quantity * $unitPrice;
                $totalAmount += $itemTotal;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $itemTotal,
                ]);
            }
            
            // Update order total
            $order->update(['total_amount' => $totalAmount]);
        }
    }
} 