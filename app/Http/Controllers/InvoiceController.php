<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SalesAgent;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function __construct()
    {
        view()->share('activeMenu', 'invoice');
    }

    public function index(Request $request)
    {
        $query = Invoice::with(['customer.salesAgent', 'customer.company']);

        if ($request->inv_no) {
            $query->where('inv_no', 'like', '%' . $request->inv_no . '%');
        }

        if ($request->sales_agents_id) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('sales_agent_id', $request->sales_agents_id);
            });
        }

        if ($request->company_id) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        if (!is_null($request->platform_id)) {
            $query->where('platform_id', $request->platform_id);
        }

        if ($request->has('lunas')) {
            $request->lunas == '1'
                ? $query->whereNotNull('lunas_at')
                : $query->whereNull('lunas_at');
        }

        if ($request->has('checked')) {
            $request->checked == '1'
                ? $query->whereNotNull('checked_finance_at')
                : $query->whereNull('checked_finance_at');
        }

        if ($request->has('cancel')) {
            $query->where('cancel', $request->cancel);
        }

        if ($request->min_amount) {
            $query->where('g_total_invoice_amount', '>=', $request->min_amount);
        }

        if ($request->max_amount) {
            $query->where('g_total_invoice_amount', '<=', $request->max_amount);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('invoice_transaction_date', [$request->start_date, $request->end_date]);
        }

        $invoices = $query->latest()->paginate(50);

        $salesAgents = SalesAgent::orderBy('nama')->get();

        $companies = Company::whereNull('deleted_at')
            ->where('blacklist', false)
            ->orderBy('nama')
            ->get();

        $customers = Customer::with(['salesAgent', 'company'])
            ->whereNull('deleted_at')
            ->where('is_blacklisted', false)
            ->where(function ($q) {
                $q->whereNull('company_id')
                    ->orWhereHas('company', function ($sub) {
                        $sub->whereNull('deleted_at')
                            ->where('blacklist', false);
                    });
            })
            ->orderBy('nama')
            ->get();

        return view('invoice.index', compact('invoices', 'salesAgents', 'companies', 'customers'));
    }


    public function create()
    {
        return view('invoice.create', [
            'invoice' => new Invoice(),
            'customers' => Customer::with(['salesAgent', 'company'])
                ->whereNull('deleted_at')
                ->where('is_blacklisted', false)
                ->where(function ($q) {
                    $q->whereHas('company', function ($sub) {
                        $sub->whereNull('deleted_at')
                            ->where('blacklist', false);
                    })->orWhereNull('company_id');
                })
                ->orderBy('nama')
                ->get(),
            'salesAgents' => SalesAgent::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'platform_id'   => 'nullable|integer',
            'invoice_transaction_date' => 'required|date',
        ]);

        // Ambil sales_agents_id dari customer
        $customer = Customer::find($validated['customer_id']);

        $validated['inv_no'] = Invoice::generateInvoiceNumber();
        $validated['g_total_invoice_amount'] = 0;
        $validated['created_by'] = session('user_id');

        $invoice = Invoice::create($validated);

        return redirect()->route('invoice.edit', $invoice->id)
            ->with('success', 'Invoice berhasil dibuat. Silakan tambahkan produk.');
    }


    public function edit(Invoice $invoice)
    {
        $invoice->load([
            'customer.salesAgent',
            'customer.company',
            'items.product'
        ]);

        $customer = Customer::with(['salesAgent', 'company'])
            ->whereNull('deleted_at')
            ->where('is_blacklisted', false)
            ->where(function ($q) {
                $q->whereHas('company', function ($sub) {
                    $sub->whereNull('deleted_at')
                        ->where('blacklist', false);
                })->orWhereNull('company_id');
            })
            ->orderBy('nama')
            ->get();

        return view('invoice.edit', [
            'invoice'     => $invoice,
            'customers' => $customer,
            'salesAgents' => SalesAgent::orderBy('nama')->get(),
            'products'    => Product::where('status', 1)->orderBy('nama')->get(), // hanya status aktif dan tidak terhapus
        ]);
    }


    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'platform_id'   => 'nullable|integer',
            'invoice_transaction_date' => 'required|date',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil diperbarui.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer.company', 'customer.salesAgent', 'items.product']);

        return view('invoice.show', compact('invoice'));
    }
}
