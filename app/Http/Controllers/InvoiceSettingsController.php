<?php

namespace App\Http\Controllers;

use App\Models\InvoiceSetting;
use Illuminate\Http\Request;

class InvoiceSettingsController extends Controller
{
    /**
     * Display the invoice settings form.
     */
    public function index()
    {
        $settings = InvoiceSetting::getSettings();
        return view('admin.invoice-settings.index', compact('settings'));
    }

    /**
     * Update the invoice settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
        ]);

        $settings = InvoiceSetting::getSettings();
        $settings->update($request->all());

        return redirect()->route('invoice-settings.index')
            ->with('success', 'Fatura ayarları başarıyla güncellendi.');
    }
}