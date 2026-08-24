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

        return view('payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('payment_methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
        ], [
            'name.required' => 'El nombre del método de pago es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede superar los 100 caracteres.',
        ]);

        // Buscar si el método de pago ya existe
        $existingPaymentMethod = PaymentMethod::where('name', $validated['name'])->first();

        // Si existe...
        if ($existingPaymentMethod) {

            // Si ya está activo, no permitimos duplicarlo
            if ($existingPaymentMethod->status === 'active') {
                return back()
                    ->withErrors([
                        'name' => 'Ya existe un método de pago activo con ese nombre.'
                    ])
                    ->withInput();
            }

            // Si estaba inactivo, lo reactivamos
            $existingPaymentMethod->update([
                'status' => 'active',
            ]);

            return redirect()
                ->route('admin.payment_methods.index')
                ->with(
                    'success',
                    'El método de pago ya había sido registrado anteriormente. Se reactivó correctamente y se conservó todo su historial.'
                );
        }

        // Si nunca existió, creamos uno nuevo
        PaymentMethod::create([
            'name' => $validated['name'],
            'status' => 'active',
        ]);

        return redirect()
            ->route('admin.payment_methods.index')
            ->with(
                'success',
                'Método de pago registrado correctamente.'
            );
    }

    public function edit(PaymentMethod $payment_method)
    {
        return view('payment_methods.edit', compact('payment_method'));
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
