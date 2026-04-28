<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentAccount;
use App\Models\PaymentType;

class PaymentDetailsController extends Controller
{
    public function index()
    {
        $paymentAccounts = PaymentAccount::orderBy('description')->paginate(10, ['*'], 'accounts_page');
        $paymentTypes = PaymentType::orderBy('description')->paginate(10, ['*'], 'types_page');
        
        return view('accounting.payment_details', compact('paymentAccounts', 'paymentTypes'));
    }

    // Payment Account CRUD
    public function storePaymentAccount(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
        ]);

        PaymentAccount::create($validated);

        return redirect()->route('accounting.payment_details')->with('success', 'Payment Account created successfully.');
    }

    public function updatePaymentAccount(Request $request, $id)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $paymentAccount = PaymentAccount::findOrFail($id);
        $paymentAccount->update($validated);

        return redirect()->route('accounting.payment_details')->with('success', 'Payment Account updated successfully.');
    }

    public function deletePaymentAccount($id)
    {
        $paymentAccount = PaymentAccount::findOrFail($id);
        $paymentAccount->delete();

        return redirect()->route('accounting.payment_details')->with('success', 'Payment Account deleted successfully.');
    }

    // Payment Type CRUD
    public function storePaymentType(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
        ]);

        PaymentType::create($validated);

        return redirect()->route('accounting.payment_details')->with('success', 'Payment Type created successfully.');
    }

    public function updatePaymentType(Request $request, $id)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
        ]);

        $paymentType = PaymentType::findOrFail($id);
        $paymentType->update($validated);

        return redirect()->route('accounting.payment_details')->with('success', 'Payment Type updated successfully.');
    }

    public function deletePaymentType($id)
    {
        $paymentType = PaymentType::findOrFail($id);
        $paymentType->delete();

        return redirect()->route('accounting.payment_details')->with('success', 'Payment Type deleted successfully.');
    }

    // API endpoints for dropdowns
    public function getPaymentAccounts()
    {
        $accounts = PaymentAccount::orderBy('description')->get();
        return response()->json(['success' => true, 'data' => $accounts]);
    }

    public function getPaymentTypes()
    {
        $types = PaymentType::orderBy('description')->get();
        return response()->json(['success' => true, 'data' => $types]);
    }
}
