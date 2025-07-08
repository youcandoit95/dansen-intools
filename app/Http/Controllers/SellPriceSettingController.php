<?php

namespace App\Http\Controllers;

use App\Models\SellPriceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellPriceSettingController extends Controller
{

    public function __construct()
    {
        view()->share('activeMenu', 'sell-price-settings');
    }

    public function index()
    {
        $activeMenu = 'sell-price-settings';

        // Pengaturan aktif
        $currentSetting = SellPriceSetting::whereNull('deleted_at')
            ->latest('created_at')
            ->first();

        // Riwayat pengaturan sebelumnya (soft deleted)
        $history = SellPriceSetting::onlyTrashed()
            ->with('creator', 'deleter')
            ->orderByDesc('created_at')
            ->get();

        return view('sell_price_settings.index', compact('activeMenu', 'currentSetting', 'history'));
    }

    public function create()
    {
        $activeMenu = 'sell-price-settings';
        $lastSetting = SellPriceSetting::whereNull('deleted_at')->latest()->first();

        return view('sell_price_settings.create', compact('activeMenu', 'lastSetting'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'online'   => 'required|integer|min:0|max:100',
            'offline'  => 'required|integer|min:0|max:100',
            'reseller' => 'required|integer|min:0|max:100',
            'resto'    => 'required|integer|min:0|max:100',
            'bottom'   => 'required|integer|min:0|max:100',
        ]);

        $userId = session('user_id');

        // Soft delete existing settings
        SellPriceSetting::whereNull('deleted_at')->update([
            'deleted_at' => now(),
            'deleted_by' => $userId,
        ]);

        // Create new setting
        SellPriceSetting::create(array_merge($data, ['created_by' => $userId]));

        return redirect()->route('sell-price-settings.index')->with('success', 'Pengaturan harga jual berhasil disimpan.');
    }
}
