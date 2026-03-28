<?php

namespace App\Nova\Metrics;

use App\Models\Product;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Nova;

class AvgProductPrice extends Value
{
    /**
     * Calculate the value of the metric.
     */
    public function calculate(NovaRequest $request)
    {
        $average = $this->average($request, Product::class, 'price');

        return $average
            ->prefix('$')               // Show as currency
            ->suffix(' per product')    // Add descriptive unit
            ->format('0,0.00');
    }

    /**
     * Name of the metric shown on the dashboard.
     */
    public function name(): string
    {
        return 'Average Product Price';
    }

    /**
     * Available ranges for the metric.
     */
    public function ranges(): array
    {
        return [
            30 => Nova::__('Last 30 Days'),
            60 => Nova::__('Last 60 Days'),
            365 => Nova::__('Last 365 Days'),
            'TODAY' => Nova::__('Today'),
            'MTD' => Nova::__('Month To Date'),
            'QTD' => Nova::__('Quarter To Date'),
            'YTD' => Nova::__('Year To Date'),
        ];
    }

    /**
     * Determine caching time for the metric.
     */
    public function cacheFor()
    {
        return now()->addMinutes(10); // Cache 10 minutes for performance
    }

    /**
     * Optional description under the value.
     */
    public function description(): ?string
    {
        return 'Average price of all products in your catalog.';
    }
}
