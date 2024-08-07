<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BudgetPayment;
use App\Models\budget;
use Carbon\Carbon; 

class financialStatisticsController extends Controller 
{
   
    private function calculateAmountRemaining($startDate, $endDate)
    {
        $amountRemaining = Budget::where('hired', 1)
                                ->where('payed', 0)
                                 ->get()
                                 ->sum(function ($budget) {
                                     return $budget->amount - $budget->paid_amount;
                                 });

         return round((float)$amountRemaining, 2);
    }

    private function calculateAmountReceived($startDate, $endDate)
    {
        $amountReceived = BudgetPayment::where('created_at', '>=', $startDate)
                                   ->where('created_at', '<=', $endDate)
                                   ->sum('value');

        return round((float)$amountReceived, 2);
    }

    private function calculateAverageTicket($days)
    {
        $daysAgo = Carbon::now()->subDays($days);

        $averageTicket = Budget::where('created_at', '>=', $daysAgo)
                               ->avg('amount');

        return round((float)$averageTicket, 2);
    }

    public function getFinancialSummary(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $daysAgo = $request->input('days_ago');

        $amountReceived = $this->calculateAmountReceived($startDate, $endDate);
        $amountRemaining = $this->calculateAmountRemaining($startDate, $endDate);
        $averageTicket = $this->calculateAverageTicket($daysAgo);

        return response()->json([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'amount_received' => $amountReceived,
            'amount_remaining' => $amountRemaining,
            'average_ticket' => $averageTicket
        ]);
    }



    private function getEarningsData($period)
    {
        switch ($period) {
            case 'last_30_days':
                $startDate = Carbon::now()->subDays(29)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                $earnings = BudgetPayment::selectRaw('DATE(created_at) as date, SUM(value) as total')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'period' => Carbon::parse($item->date)->format('d-m'),
                            'value' => (float)$item->total
                        ];
                    });
                break;

            case 'last_6_months':
                $startDate = Carbon::now()->subMonths(5)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $earnings = BudgetPayment::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(value) as total')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get()
                    ->map(function ($item) {
                        $months = [
                            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
                            5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
                            9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
                        ];
                        return [
                            'period' => $months[$item->month] . ' ' . $item->year,
                            'value' => (float)$item->total
                        ];
                    });
                break;

            case 'last_year':
                $startDate = Carbon::now()->subYear()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $earnings = BudgetPayment::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(value) as total')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get()
                    ->map(function ($item) {
                        $months = [
                            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
                            5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
                            9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
                        ];
                        return [
                            'period' => $months[$item->month] . ' ' . $item->year,
                            'value' => (float)$item->total
                        ];
                    });
                break;

            default:
                return response()->json(['error' => 'Invalid period'], 400);
        }

       
        $earnings = $earnings->map(function ($item) {
            $item['period'] = str_replace('\\', '', $item['period']);
            return $item;
        });

        return $earnings;
    }

    public function getEarnings(Request $request)
    {
        $period = $request->input('period', 'last_30_days');

        $earnings = $this->getEarningsData($period);

        return response()->json($earnings);
    }
}