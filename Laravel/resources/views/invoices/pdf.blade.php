<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .company-info {
            flex: 1;
        }
        .invoice-info {
            flex: 1;
            text-align: right;
        }
        .logo {
            max-width: 200px;
            max-height: 100px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .totals {
            float: right;
            width: 300px;
        }
        .totals table {
            width: 100%;
        }
        .totals td {
            border: none;
        }
        .totals tr:last-child td {
            font-weight: bold;
            border-top: 1px solid #ddd;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
        }
        .addresses {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .address {
            flex: 1;
            margin-right: 20px;
        }
        .address:last-child {
            margin-right: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            @if($settings->logo_path)
                <img src="{{ storage_path('app/' . $settings->logo_path) }}" alt="Company Logo" class="logo">
            @endif
            <h2>{{ $settings->company_name }}</h2>
            <p>{{ $settings->address }}</p>
            <p>Phone: {{ $settings->phone }}</p>
            <p>Email: {{ $settings->email }}</p>
            @if($settings->tax_id)
                <p>Tax ID: {{ $settings->tax_id }}</p>
            @endif
        </div>
        <div class="invoice-info">
            <h1>INVOICE</h1>
            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Date:</strong> {{ $invoice->invoice_date->format('Y-m-d') }}</p>
            <p><strong>Due Date:</strong> {{ $invoice->due_date->format('Y-m-d') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
        </div>
    </div>

    <div class="addresses">
        <div class="address">
            <h3>Billing Address</h3>
            <p>{{ $invoice->billing_address }}</p>
        </div>
        <div class="address">
            <h3>Shipping Address</h3>
            <p>{{ $invoice->shipping_address }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->product->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td>${{ number_format($invoice->total_amount - $invoice->tax_amount - $invoice->shipping_amount + $invoice->discount_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Tax:</td>
                <td>${{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Shipping:</td>
                <td>${{ number_format($invoice->shipping_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Discount:</td>
                <td>-${{ number_format($invoice->discount_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Total:</td>
                <td>${{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        @if($settings->terms)
            <h3>Terms & Conditions</h3>
            <p>{{ $settings->terms }}</p>
        @endif

        @if($settings->notes)
            <h3>Notes</h3>
            <p>{{ $settings->notes }}</p>
        @endif

        @if($invoice->notes)
            <h3>Invoice Notes</h3>
            <p>{{ $invoice->notes }}</p>
        @endif
    </div>
</body>
</html> 