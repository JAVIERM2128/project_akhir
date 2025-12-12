<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class SalesReportController extends Controller
{
    /**
     * Display the sales report.
     */
    public function index(Request $request)
    {
        // Ambil filter dari request
        $type = $request->input('type', 'daily'); // daily, weekly, monthly
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Set default range jika tidak disediakan
        if (!$startDate) {
            $startDate = now()->subDays(30)->format('Y-m-d');
        }
        if (!$endDate) {
            $endDate = now()->format('Y-m-d');
        }

        // Query untuk mendapatkan data laporan
        $reportData = $this->generateReport($type, $startDate, $endDate);

        // Hitung total statistik
        $totalTransactions = $this->getTotalTransactions($startDate, $endDate);
        $totalSales = $this->getTotalSales($startDate, $endDate);
        $totalRevenue = $this->getTotalRevenue($startDate, $endDate);

        return view('admin.reports.sales', compact(
            'reportData',
            'totalTransactions',
            'totalSales',
            'totalRevenue',
            'type',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate report data based on type and date range.
     */
    private function generateReport($type, $startDate, $endDate)
    {
        // Query efisien dengan JOIN dan SUM
        $query = TransactionItem::select(
                DB::raw("DATE(transactions.created_at) as date"),
                DB::raw("COUNT(transactions.id) as transaction_count"),
                DB::raw("SUM(transaction_items.quantity) as total_quantity"),
                DB::raw("SUM(transaction_items.price * transaction_items.quantity) as total_revenue")
            )
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('transactions.status', 'paid') // hanya transaksi yang dibayar
            ->groupBy(DB::raw("DATE(transactions.created_at)"))
            ->orderBy('date', 'asc');

        $results = $query->get();

        // Sesuaikan grup berdasarkan tipe laporan
        switch ($type) {
            case 'weekly':
                return $this->groupByWeek($results);
            case 'monthly':
                return $this->groupByMonth($results);
            default: // daily
                return $results;
        }
    }

    /**
     * Group data by week.
     */
    private function groupByWeek($data)
    {
        $grouped = [];
        foreach ($data as $item) {
            $week = date('W', strtotime($item->date));
            $year = date('Y', strtotime($item->date));
            $key = $year . '-W' . $week;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'date' => 'Week ' . $week . ' of ' . $year,
                    'transaction_count' => 0,
                    'total_quantity' => 0,
                    'total_revenue' => 0
                ];
            }

            $grouped[$key]['transaction_count'] += $item->transaction_count;
            $grouped[$key]['total_quantity'] += $item->total_quantity;
            $grouped[$key]['total_revenue'] += $item->total_revenue;
        }

        return collect($grouped);
    }

    /**
     * Group data by month.
     */
    private function groupByMonth($data)
    {
        $grouped = [];
        foreach ($data as $item) {
            $month = date('m', strtotime($item->date));
            $year = date('Y', strtotime($item->date));
            $key = $year . '-' . $month;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'date' => date('F Y', strtotime($item->date)),
                    'transaction_count' => 0,
                    'total_quantity' => 0,
                    'total_revenue' => 0
                ];
            }

            $grouped[$key]['transaction_count'] += $item->transaction_count;
            $grouped[$key]['total_quantity'] += $item->total_quantity;
            $grouped[$key]['total_revenue'] += $item->total_revenue;
        }

        return collect($grouped);
    }

    /**
     * Get total number of transactions in date range.
     */
    private function getTotalTransactions($startDate, $endDate)
    {
        return Transaction::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'paid')
            ->count();
    }

    /**
     * Get total sales (jumlah produk terjual) in date range.
     */
    private function getTotalSales($startDate, $endDate)
    {
        return TransactionItem::join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('transactions.status', 'paid')
            ->sum('transaction_items.quantity');
    }

    /**
     * Get total revenue in date range.
     */
    private function getTotalRevenue($startDate, $endDate)
    {
        return TransactionItem::join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->whereBetween('transactions.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('transactions.status', 'paid')
            ->sum(DB::raw('transaction_items.price * transaction_items.quantity'));
    }

    /**
     * Export report to Excel.
     */
    public function export(Request $request)
    {
        $type = $request->input('type', 'daily');
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Ambil data laporan
        $reportData = $this->generateReport($type, $startDate, $endDate);
        $totalTransactions = $this->getTotalTransactions($startDate, $endDate);
        $totalSales = $this->getTotalSales($startDate, $endDate);
        $totalRevenue = $this->getTotalRevenue($startDate, $endDate);

        // Siapkan data untuk export
        $data = [
            'reportData' => $reportData,
            'summary' => [
                'total_transactions' => $totalTransactions,
                'total_sales' => $totalSales,
                'total_revenue' => $totalRevenue
            ],
            'filters' => [
                'type' => $type,
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'user' => Auth::user()->name ?? 'System'
        ];

        // Return Excel file
        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function collection()
            {
                $rows = [];

                // Tambahkan header
                $rows[] = ['LAPORAN PENJUALAN'];
                $rows[] = ['Periode: ' . $this->data['filters']['start_date'] . ' s.d ' . $this->data['filters']['end_date']];
                $rows[] = ['Tipe: ' . ucfirst($this->data['filters']['type'])];
                $rows[] = ['Dicetak oleh: ' . $this->data['user']];
                $rows[] = [''];

                // Tambahkan summary
                $rows[] = ['SUMMARY'];
                $rows[] = ['Total Transaksi', $this->data['summary']['total_transactions']];
                $rows[] = ['Total Penjualan', $this->data['summary']['total_sales']];
                $rows[] = ['Total Pendapatan', 'Rp ' . number_format($this->data['summary']['total_revenue'], 2)];
                $rows[] = [''];

                // Tambahkan headings untuk detail
                $rows[] = ['Periode', 'Jumlah Transaksi', 'Jumlah Produk Terjual', 'Pendapatan'];

                // Tambahkan data detail
                foreach ($this->data['reportData'] as $data) {
                    $rows[] = [
                        $data->date ?? $data['date'],
                        $data->transaction_count ?? $data['transaction_count'],
                        $data->total_quantity ?? $data['total_quantity'],
                        'Rp ' . number_format(($data->total_revenue ?? $data['total_revenue']), 2)
                    ];
                }

                return collect($rows);
            }

            public function headings(): array
            {
                return [];
            }
        }, 'sales_report_' . $startDate . '_to_' . $endDate . '.xlsx');
    }

    /**
     * Export report to PDF.
     */
    public function pdf(Request $request)
    {
        $type = $request->input('type', 'daily');
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Ambil data laporan
        $reportData = $this->generateReport($type, $startDate, $endDate);
        $totalTransactions = $this->getTotalTransactions($startDate, $endDate);
        $totalSales = $this->getTotalSales($startDate, $endDate);
        $totalRevenue = $this->getTotalRevenue($startDate, $endDate);

        // Siapkan data untuk PDF
        $data = [
            'reportData' => $reportData,
            'summary' => [
                'total_transactions' => $totalTransactions,
                'total_sales' => $totalSales,
                'total_revenue' => $totalRevenue
            ],
            'filters' => [
                'type' => $type,
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'user' => Auth::user()->name ?? 'System',
            'now' => now()->format('d M Y H:i:s')
        ];

        $pdf = Pdf::loadView('admin.reports.sales_pdf', $data);
        return $pdf->download('sales_report_' . $startDate . '_to_' . $endDate . '.pdf');
    }
}
