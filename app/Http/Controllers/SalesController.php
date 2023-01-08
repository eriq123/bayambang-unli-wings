<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public $salesFilter = [
        'd' => 'daily',
        'W' => 'weekly',
        'M' => 'monthly',
    ];

    private function getSlugKey($slug)
    {
        $salesFilter = array_flip($this->salesFilter);
        return $salesFilter[$slug];
    }

    private function getSales($slug)
    {
        $deliveredId = Status::where('name', 'Delivered')->first()->id;
        $slugKey = $this->getSlugKey($slug);

        return
            Order::where('shop_id', Auth::user()->shop_id)
            ->where('status_id', $deliveredId)
            ->where('isActive', 1)
            ->orderBy('updated_at')
            ->get()
            ->groupBy(function ($value) use ($slugKey) {
                return Carbon::parse($value->updated_at)->format($slugKey);
            });
    }

    private function getDataPoints($slug, $sales)
    {
        $report = [];
        $labelFormat = 'M';

        $count = 14;
        if ($slug === 'monthly') {
            $count = 12;
        }

        if ($slug === 'daily') {
            $labelFormat = 'm/d';
        }

        if ($slug === 'weekly') {
            $labelFormat = 'm/d/y';
        }

        foreach ($sales as $sale) {
            $total = 0;
            $temp = [];
            foreach ($sale as $order) {
                $temp['updated_at'] = $order->updated_at;
                $temp['label'] = $order->updated_at->format($labelFormat);
                $total += $order->total;
            }
            $temp['y'] = $total;
            $report[] = $temp;
        }

        $reportCollection = collect($report)
            ->sortByDesc('updated_at')
            ->take($count)
            ->sortBy('updated_at');

        return $reportCollection->values()->all();
    }

    public function index($slug = null)
    {
        if ($slug === null) return redirect(route('sales.index', ['slug' => 'daily']));

        $salesFilter = $this->salesFilter;
        $status = Status::all();
        $dataPoints = $this->getDataPoints($slug, $this->getSales($slug));

        return view('admin.sales', compact('salesFilter', 'status', 'dataPoints'));
    }
}
