<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SalesAgent;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function __construct()
    {
        view()->share('activeMenu', 'invoice');
    }

    public function index(Request $request)
    {
        $query = Invoice::with(['salesAgent', 'company', 'customer']);

        if ($request->inv_no) {
            $query->where('inv_no', 'like', '%' . $request->inv_no . '%');
        }

        if ($request->sales_agents_id) {
            $query->where('sales_agents_id', $request->sales_agents_id);
        }

        if ($request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->platform_id !== null) {
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
        $companies = Company::orderBy('nama')->get();
        $customers = Customer::orderBy('nama')->get();

        return view('invoice.index', compact('invoices', 'salesAgents', 'companies', 'customers'));
    }
}
