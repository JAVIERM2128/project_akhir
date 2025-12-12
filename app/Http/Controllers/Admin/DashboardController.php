<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Topup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total produk
        $totalProducts = Product::count();

        // Total user
        $totalUsers = User::count();

        // Total transaksi
        $totalTransactions = Transaction::count();

        // Total topup pending
        $totalTopupPending = Topup::where('status', 'pending')->count();

        // Data untuk grafik transaksi bulanan
        $monthlyTransactions = Transaction::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', 'paid') // hanya transaksi yang sudah dibayar
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
            ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalUsers',
            'totalTransactions',
            'totalTopupPending',
            'monthlyTransactions'
        ));
    }
}
