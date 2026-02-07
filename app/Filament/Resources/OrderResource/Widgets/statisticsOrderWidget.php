<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Facades\DB;

class statisticsOrderWidget extends BaseWidget

{
    use InteractsWithPageFilters;
    protected static ?string $pollingInterval = '10s';
    // protected ?string $heading = 'Analytics';
    // protected ?string $description = 'An overview of some analytics.';


    public function getHeading(): ?string

    {
        return (__("Orders Analytics"));
    }

    protected function getDescription(): ?string
    {
        return __('An overview of order statistics with trends and financials.');
    }


    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        // 🔹 Counts by order status
        $statusCounts = Order::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($startDate)))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($endDate)))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // 🔹 Counts by payment method
        $paymentMethodsCounts = Order::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($startDate)))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($endDate)))
            ->selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method');

        // 🔹 Counts by payment status
        $paymentStatusCounts = Order::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($startDate)))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($endDate)))
            ->selectRaw('payment_status, COUNT(*) as count')
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status');

        // 🔹 Trends by status
        $trendData = Order::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($startDate)))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($endDate)))
            ->selectRaw('status, DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('status', DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get()
            ->groupBy('status');

        $getTrend = fn($status) => $trendData->has($status)
            ? $trendData[$status]->pluck('count')->toArray()
            : [];

        // 🔹 Financial sums
        $financials = Order::query()
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($startDate)))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($endDate)))
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(total_price) as total_price,
                AVG(total_price) as average_price
            ')
            ->first();

        $stats = [
            // ==== ORDER STATUS STATS ====
            Stat::make(__('pending_orders'), $statusCounts['pending'] ?? 0)
                ->color('warning')
                ->description(__('orders_waiting_for_processing'))
                ->descriptionIcon('heroicon-o-clock')
                ->chart($getTrend('pending')),

            Stat::make(__('processing_orders'), $statusCounts['processing'] ?? 0)
                ->color('info')
                ->description(__('orders_being_processed'))
                ->descriptionIcon('heroicon-o-arrow-path')
                ->chart($getTrend('processing')),

            Stat::make(__('completed_orders'), $statusCounts['completed'] ?? 0)
                ->color('success')
                ->description(__('orders_successfully_completed'))
                ->descriptionIcon('heroicon-o-check-circle')
                ->chart($getTrend('completed')),

            Stat::make(__('cancelled_orders'), $statusCounts['cancelled'] ?? 0)
                ->color('danger')
                ->description(__('orders_cancelled_by_clients'))
                ->descriptionIcon('heroicon-o-x-circle', IconPosition::Before)
                ->chart($getTrend('cancelled')),

            // ==== PAYMENT METHOD STATS ====
            Stat::make(__('cash_orders'), $paymentMethodsCounts['cash'] ?? 0)
                ->color('success')
                ->description(__('orders_paid_with_cash'))
                ->descriptionIcon('heroicon-o-banknotes'),

            Stat::make(__('card_orders'), $paymentMethodsCounts['card'] ?? 0)
                ->color('info')
                ->description(__('orders_paid_with_card'))
                ->descriptionIcon('heroicon-o-credit-card'),

            Stat::make(__('online_orders'), $paymentMethodsCounts['online'] ?? 0)
                ->color('warning')
                ->description(__('orders_paid_online'))
                ->descriptionIcon('heroicon-o-globe-alt'),

            // ==== PAYMENT STATUS STATS ====
            Stat::make(__('paid_orders'), $paymentStatusCounts['paid'] ?? 0)
                ->color('success')
                ->description(__('orders_successfully_paid'))
                ->descriptionIcon('heroicon-o-banknotes'),

            Stat::make(__('pending_payment'), $paymentStatusCounts['pending'] ?? 0)
                ->color('warning')
                ->description(__('awaiting_payment_confirmation'))
                ->descriptionIcon('heroicon-o-clock'),

            Stat::make(__('failed_payments'), $paymentStatusCounts['failed'] ?? 0)
                ->color('danger')
                ->description(__('payment_failed'))
                ->descriptionIcon('heroicon-o-x-mark'),

            // ==== FINANCIAL STATS ====
            Stat::make(__('total_orders'), $financials->total_orders ?? 0)
                ->color('info')
                ->description(__('total_number_of_orders'))
                ->descriptionIcon('heroicon-o-shopping-cart'),

            Stat::make(__('total_revenue'), number_format($financials->total_price ?? 0, 2))
                ->color('success')
                ->description(__('sum_of_all_orders_revenue'))
                ->descriptionIcon('heroicon-o-currency-dollar'),

            Stat::make(__('average_order_value'), number_format($financials->average_price ?? 0, 2))
                ->color('info')
                ->description(__('average_price_per_order'))
                ->descriptionIcon('heroicon-o-chart-bar'),
        ];

        return $stats;
    }
}
