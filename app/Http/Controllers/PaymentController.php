<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        [$year, $m] = explode('-', $month);

        $billing_categories = \App\Models\BillingCategory::all();

        $students = Student::active()->with(['classroom', 'payments' => function ($q) use ($year, $m) {
            $q->whereYear('payment_date', $year)
              ->whereMonth('payment_date', $m)
              ->where('status', 'lunas')
              ->with('billingCategory');
        }])->get();

        return view('payments.index', compact('students', 'month', 'billing_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'billing_category_id' => 'required|exists:billing_categories,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
        ]);

        Payment::create([
            'student_id' => $request->student_id,
            'billing_category_id' => $request->billing_category_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'status' => 'lunas'
        ]);

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Data pembayaran berhasil dibatalkan.');
    }
}
