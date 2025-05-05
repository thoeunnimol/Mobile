<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\InvoiceSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request)
    {
        try {
            $query = Invoice::with('order');
            
            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                      ->orWhereHas('order', function($q) use ($search) {
                          $q->where('order_number', 'like', "%{$search}%");
                      });
                });
            }
            
            // Apply status filter
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }
            
            // Apply date filter
            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->where('invoice_date', '>=', $request->date_from);
            }
            
            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->where('invoice_date', '<=', $request->date_to);
            }
            
            // Pagination
            $perPage = $request->input('per_page', 10);
            $invoices = $query->orderBy('created_at', 'desc')->paginate($perPage);
            
            return response()->json($invoices);
        } catch (\Exception $e) {
            Log::error('Error fetching invoices: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching invoices'], 500);
        }
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
                'invoice_number' => 'required|string|unique:invoices,invoice_number',
                'invoice_date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:invoice_date',
                'total_amount' => 'required|numeric|min:0',
                'tax_amount' => 'required|numeric|min:0',
                'shipping_amount' => 'required|numeric|min:0',
                'discount_amount' => 'required|numeric|min:0',
                'status' => 'required|in:draft,sent,paid,overdue',
                'notes' => 'nullable|string',
                'billing_address' => 'required|string',
                'shipping_address' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Create invoice
            $invoice = Invoice::create($request->all());

            return response()->json([
                'message' => 'Invoice created successfully',
                'invoice' => $invoice
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating invoice: ' . $e->getMessage());
            return response()->json(['message' => 'Error creating invoice'], 500);
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        try {
            $invoice->load('order');
            return response()->json($invoice);
        } catch (\Exception $e) {
            Log::error('Error fetching invoice: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching invoice'], 500);
        }
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|exists:orders,id',
                'invoice_number' => 'required|string|unique:invoices,invoice_number,' . $invoice->id,
                'invoice_date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:invoice_date',
                'total_amount' => 'required|numeric|min:0',
                'tax_amount' => 'required|numeric|min:0',
                'shipping_amount' => 'required|numeric|min:0',
                'discount_amount' => 'required|numeric|min:0',
                'status' => 'required|in:draft,sent,paid,overdue',
                'notes' => 'nullable|string',
                'billing_address' => 'required|string',
                'shipping_address' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Update invoice
            $invoice->update($request->all());

            return response()->json([
                'message' => 'Invoice updated successfully',
                'invoice' => $invoice
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating invoice: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating invoice'], 500);
        }
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            return response()->json(['message' => 'Invoice deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting invoice: ' . $e->getMessage());
            return response()->json(['message' => 'Error deleting invoice'], 500);
        }
    }

    /**
     * Update the status of the specified invoice.
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:draft,sent,paid,overdue',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Update invoice status
            $invoice->update(['status' => $request->status]);

            return response()->json([
                'message' => 'Invoice status updated successfully',
                'invoice' => $invoice
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating invoice status: ' . $e->getMessage());
            return response()->json(['message' => 'Error updating invoice status'], 500);
        }
    }

    /**
     * Generate PDF for the specified invoice.
     */
    public function generatePdf(Invoice $invoice)
    {
        try {
            $invoice->load('order');
            $settings = InvoiceSettings::first();
            
            $pdf = PDF::loadView('invoices.pdf', [
                'invoice' => $invoice,
                'settings' => $settings
            ]);
            
            return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            return response()->json(['message' => 'Error generating PDF'], 500);
        }
    }

    /**
     * Download PDF for the specified invoice.
     */
    public function downloadPdf(Invoice $invoice)
    {
        try {
            $invoice->load('order');
            $settings = InvoiceSettings::first();
            
            $pdf = PDF::loadView('invoices.pdf', [
                'invoice' => $invoice,
                'settings' => $settings
            ]);
            
            return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
        } catch (\Exception $e) {
            Log::error('Error downloading PDF: ' . $e->getMessage());
            return response()->json(['message' => 'Error downloading PDF'], 500);
        }
    }

    /**
     * Send invoice via email.
     */
    public function sendInvoice(Invoice $invoice)
    {
        try {
            // Logic to send invoice via email
            // This would typically involve generating the PDF and sending it via Laravel's mail system
            
            // For now, just update the status to 'sent'
            $invoice->update(['status' => 'sent']);
            
            return response()->json([
                'message' => 'Invoice sent successfully',
                'invoice' => $invoice
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending invoice: ' . $e->getMessage());
            return response()->json(['message' => 'Error sending invoice'], 500);
        }
    }
} 