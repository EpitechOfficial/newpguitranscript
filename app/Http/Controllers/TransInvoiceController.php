<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Courier;
use App\Models\RequestType;
use App\Models\TransDetailsNew;
use App\Models\TransInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TransInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user   = Auth::user();
        $matric = $user->matric;

        $invoiceNumbers = TransDetailsNew::where('matric', $matric)
            ->pluck('email');


        $fullNameRecord = TransDetailsNew::where('matric', $matric)->first();
        $fullName       = $fullNameRecord->Surname . ' ' . $fullNameRecord->Othernames;

        $invoices = TransInvoice::where('appno', $matric)
            ->whereIn('invoiceno', $invoiceNumbers)
            ->orderBy('id', 'desc')
            ->get();

        return view('invoices.index', compact('invoices', 'fullName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Create the TransInvoice record
            $trans = TransInvoice::create($request->all());

            // Log to check the invoiceno value
            Log::info('Invoice Number: ' . $trans->invoiceno);

            // Retrieve the authenticated user and their matric
            $user   = Auth::user();
            $matric = $user->matric;

            // Find the TransDetailsNew record where email is null
            TransDetailsNew::where('matric', $matric)
                ->where('email', null)
                ->update(['email' => $trans->invoiceno]);

            Cart::where('matric', $matric)->delete();

            // Update courier records with invoice number
            Courier::where('appno', $matric)
                ->where('invoiceno', 0)
                ->update(['invoiceno' => $trans->invoiceno]);

            Cart::where('matric', $matric)->delete();

            // Redirect with success message
            return redirect()->route('transinvoice.index')->with('success', 'Checkout successfully.');

        } catch (\Exception $e) {
            // Handle exceptions
            Log::error('Error creating TransInvoice: ' . $e->getMessage());
            return redirect()->route('transinvoice.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user   = Auth::user();
        $matric = $user->matric;

        // $prevApp = PrevApp::where('matric', $matric)->first();
        // if (!$prevApp) {
        //     return response()->json(['error' => 'User not found in prev_app table.'], 404);
        // }
        // $newRecord = NewRecord::find($prevApp->user_id);

        // if (!$newRecord) {
        //     return response()->json(['error' => 'User not found in new table.'], 404);
        // }

        $newRecord = TransDetailsNew::where('matric', $matric)->first();
        $fullName  = $newRecord->Surname . ' ' . $newRecord->Othernames;
        $invoice   = TransInvoice::findOrFail($id);

        return view('invoices.invoice', compact('invoice', 'fullName'));
    }
    public function showReceipt(string $id)
    {
        $user   = Auth::user();
        $matric = $user->matric;

        // $prevApp = PrevApp::where('matric', $matric)->first();
        // if (!$prevApp) {
        //     return response()->json(['error' => 'User not found in prev_app table.'], 404);
        // }
        // $newRecord = NewRecord::find($prevApp->user_id);

        // if (!$newRecord) {
        //     return response()->json(['error' => 'User not found in new table.'], 404);
        // }

        $newRecord = TransDetailsNew::where('matric', $matric)->first();
        $fullName  = $newRecord->Surname . ' ' . $newRecord->Othernames;
        $invoice   = TransInvoice::findOrFail($id);

        return view('invoices.receipt', compact('invoice', 'fullName'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user   = Auth::user();
        $matric = $user->matric;

        // $prevApp = PrevApp::where('matric', $matric)->first();
        // if (!$prevApp) {
        //     return response()->json(['error' => 'User not found in prev_app table.'], 404);
        // }
        // $newRecord = NewRecord::find($prevApp->user_id);

        // if (!$newRecord) {
        //     return response()->json(['error' => 'User not found in new table.'], 404);
        // }
        $newRecord = TransDetailsNew::where('matric', $matric)->first();
        $fullName  = $newRecord->Surname . ' ' . $newRecord->Othernames;
        $invoice   = TransInvoice::findOrFail($id);
        $requests  = RequestType::all();
        return view('invoices.edit', compact('invoice', 'requests', 'fullName'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice = TransInvoice::findOrFail($id);

        $transactions = $request->input('transactions', []);

        $purposes = [];
        $amounts  = [];
        $copies   = [];
        $total    = 0;

        foreach ($transactions as $transaction) {
            $purpose = $transaction['purpose'] ?? $transaction['original_purpose'];
            $amount  = $transaction['amount'] ?? $transaction['original_amount'];
            $copy    = $transaction['copy'] ?? $transaction['original_copy'];
            $total += $transaction['total'];

            $purposes[] = $purpose;
            $amounts[]  = $amount;
            $copies[]   = $copy;
        }

        // Concatenate the values
        $concatenatedPurposes = implode(',', $purposes);
        $concatenatedAmounts  = implode(',', $amounts);
        $concatenatedCopies   = implode(',', $copies);

        // Update the invoice record
        $invoice->update([
            'purpose'       => $concatenatedPurposes . ',',
            'dy'            => $concatenatedAmounts . ',',
            'mth'           => $concatenatedCopies . ',',
            'amount_charge' => $total,
        ]);

        return redirect()->route('transinvoice.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
