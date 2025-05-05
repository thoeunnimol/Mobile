<?php

namespace App\Http\Controllers;

use App\Models\InvoiceSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class InvoiceSettingsController extends Controller
{
    /**
     * Display the invoice settings.
     */
    public function index()
    {
        try {
            $settings = InvoiceSettings::first();
            
            if (!$settings) {
                // Create default settings if none exist
                $settings = InvoiceSettings::create([
                    'company_name' => 'Your Company',
                    'tax_id' => '',
                    'address' => 'Your Address',
                    'phone' => 'Your Phone',
                    'email' => 'your@email.com',
                    'prefix' => 'INV-',
                    'terms' => '',
                    'notes' => '',
                ]);
            }
            
            return response()->json($settings);
        } catch (\Exception $e) {
            Log::error('Error fetching invoice settings: ' . $e->getMessage());
            return response()->json(['message' => 'Error fetching invoice settings'], 500);
        }
    }

    /**
     * Store or update invoice settings.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|max:255',
                'tax_id' => 'nullable|string|max:50',
                'address' => 'required|string',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'prefix' => 'required|string|max:10',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'terms' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Get existing settings or create new ones
            $settings = InvoiceSettings::first() ?? new InvoiceSettings();
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                try {
                    // Delete old logo if exists
                    if ($settings->logo_path) {
                        $oldPath = str_replace('public/', '', $settings->logo_path);
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                    
                    // Store new logo
                    $logoFile = $request->file('logo');
                    $filename = time() . '_' . $logoFile->getClientOriginalName();
                    $path = $logoFile->storeAs('invoice-logos', $filename, 'public');
                    
                    if (!$path) {
                        throw new \Exception('Failed to store logo file');
                    }
                    
                    $settings->logo_path = 'public/' . $path;
                    
                } catch (\Exception $e) {
                    Log::error('Error handling logo upload: ' . $e->getMessage());
                    return response()->json([
                        'message' => 'Error uploading logo',
                        'error' => $e->getMessage()
                    ], 500);
                }
            }
            
            // Update other fields
            $settings->company_name = $request->company_name;
            $settings->tax_id = $request->tax_id;
            $settings->address = $request->address;
            $settings->phone = $request->phone;
            $settings->email = $request->email;
            $settings->prefix = $request->prefix;
            $settings->terms = $request->terms;
            $settings->notes = $request->notes;
            
            if (!$settings->save()) {
                throw new \Exception('Failed to save settings to database');
            }

            return response()->json([
                'message' => 'Invoice settings saved successfully',
                'settings' => $settings
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error saving invoice settings: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Error saving invoice settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 