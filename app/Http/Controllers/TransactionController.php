<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\CheckoutRequest;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Process checkout form submission.
     *
     * @param CheckoutRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkout(CheckoutRequest $request)
    {
        try {
            $transaction = $this->transactionService->processCheckout(
                $request->validated(),
                Auth::id()
            );

            return redirect()->route('payment.waiting', $transaction->invoice);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display general order status page.
     *
     * @return \Illuminate\View\View
     */
    public function statusPage()
    {
        return view('status');
    }

    /**
     * Search transaction by invoice.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $request->validate([
            'invoice' => 'required|string'
        ]);

        $transaction = Transaction::with(['game', 'paymentMethod', 'nominal'])
            ->where('invoice', trim($request->invoice))
            ->first();

        if (!$transaction) {
            return back()->withErrors([
                'invoice' => 'Nomor invoice tidak ditemukan.'
            ])->withInput();
        }

        return view('status', compact('transaction'));
    }
}
