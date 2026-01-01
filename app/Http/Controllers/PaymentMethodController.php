<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentMethod::query();

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $paymentMethods = $query->get();

        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment_methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100|unique:payment_methods,name',
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment_methods.index')
            ->with('success', 'Método de pago registrado correctamente.');
    }

    public function edit(PaymentMethod $payment_method)
    {
        return view('admin.payment_methods.edit', compact('payment_method'));
    }

    public function update(Request $request, PaymentMethod $payment_method)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100|unique:payment_methods,name,' . $payment_method->id,
        ]);

        $payment_method->update($validated);

        return redirect()->route('admin.payment_methods.index')
            ->with('success', 'Método de pago actualizado correctamente.');
    }


    public function deactivate(PaymentMethod $payment_method)
    {
        $payment_method->update(['status' => 'inactive']);
        return response()->json(['success' => true]);
    }

    public function activate(PaymentMethod $payment_method)
    {
        $payment_method->update(['status' => 'active']);
        return response()->json(['success' => true]);
    }
}
