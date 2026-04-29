<?php

namespace App\Http\Controllers;

use App\Models\Barn;
use App\Models\BarnSupply;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $barn = Barn::where('barn_owner_id', $user->id)->first();

        if (!$barn) {
            return view('barn_owner_dashboard.index', [
                'barnName'           => $user->first_name . "'s Farm",
                'totalSupplies'      => 0,
                'suppliesAddedToday' => 0,
                'suppliesUsedToday'  => 0,
                'categoryData'       => collect(),
                'lowStockSupplies'   => collect(),
            ]);
        }

        // KPI 1: Total supply types
        $totalSupplies = BarnSupply::where('barn_id', $barn->id)
            ->where('supply_status', 'active')
            ->count();

        // KPI 2: Supplies Added Today
        $suppliesAddedToday = InventoryTransaction::where('barn_id', $barn->id)
            ->where('transaction_type', 'stock_in')
            ->whereDate('created_at', today())
            ->sum('quantity');

        // KPI 3: Supplies Used Today
        $suppliesUsedToday = InventoryTransaction::where('barn_id', $barn->id)
            ->where('transaction_type', 'stock_out')
            ->whereDate('created_at', today())
            ->sum('quantity');

        // Pie Chart: Supply by Category
        $categoryData = BarnSupply::where('barn_supplies.barn_id', $barn->id)
            ->where('supply_status', 'active')
            ->join('categories', 'barn_supplies.category_id', '=', 'categories.id')
            ->select('categories.category_name', DB::raw('COUNT(*) as total'))
            ->groupBy('categories.id', 'categories.category_name')
            ->orderByDesc('total')
            ->get();

        // Low Stock Alerts
        $lowStockSupplies = BarnSupply::with('category')
            ->where('barn_id', $barn->id)
            ->where('supply_status', 'active')
            ->whereColumn('stock', '<=', 'reorder_level')
            ->orderBy('stock', 'asc')
            ->get();

        return view('barn_owner_dashboard.index', [
            'barnName'           => $barn->barn_name,
            'totalSupplies'      => $totalSupplies,
            'suppliesAddedToday' => $suppliesAddedToday,
            'suppliesUsedToday'  => $suppliesUsedToday,
            'categoryData'       => $categoryData,
            'lowStockSupplies'   => $lowStockSupplies,
        ]);
    }

    // KPI MODAL: Inventory On Hand
    public function kpiInventory()
    {
        $barn = Barn::where('barn_owner_id', Auth::id())->first();

        if (!$barn) return response()->json([]);

        $supplies = BarnSupply::with('category')
            ->where('barn_id', $barn->id)
            ->where('supply_status', 'active')
            ->orderBy('supply_name')
            ->get()
            ->map(function ($s) {
                $cat    = $s->category->category_name ?? '—';
                $isOut  = $s->stock === 0;
                $isLow  = $s->stock <= $s->reorder_level;
                $status = $isOut ? 'Out of Stock' : ($isLow ? 'Low Stock' : 'In Stock');

                return [
                    'name'     => $s->supply_name,
                    'detail'   => $cat,
                    'status'   => $status,
                    'value'    => $s->stock,
                    'sub'      => 'units in stock',
                ];
            });

        return response()->json($supplies);
    }

    // KPI MODAL: Supplies Added Today
    public function kpiStockIn()
    {
        $barn = Barn::where('barn_owner_id', Auth::id())->first();

        if (!$barn) {
            return response()->json([]);
        }

        $transactions = InventoryTransaction::with(['supply', 'user', 'supplier'])
            ->where('barn_id', $barn->id)
            ->where('transaction_type', 'stock_in')
            ->whereDate('created_at', today())
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($t) {
                $supplierName = $t->supplier ? $t->supplier->supplier_name : '—';

                return [
                    'name'      => $t->supply->supply_name ?? '—',
                    'detail'    => ($t->user->first_name ?? '?') . ' ' . ($t->user->last_name ?? '') .
                                   ' · ' . $t->created_at->format('g:i A'),
                    'status'    => 'stock_in',
                    'value'     => '+' . $t->quantity,
                    'unit_cost' => $t->unit_cost !== null ? number_format($t->unit_cost, 2) : null,
                    'sub'       => $supplierName !== '—' 
                                   ? "Supplier: " . $supplierName 
                                   : ($t->remarks ?? 'No remarks'),
                    'supplier_name' => $supplierName,   // extra for future use if needed
                ];
            });

        return response()->json($transactions);
    }

    // KPI MODAL: Supplies Used Today
    public function kpiStockOut()
    {
        $barn = Barn::where('barn_owner_id', Auth::id())->first();

        if (!$barn) {
            return response()->json([]);
        }

        $transactions = InventoryTransaction::with(['supply', 'user'])
            ->where('barn_id', $barn->id)
            ->where('transaction_type', 'stock_out')
            ->whereDate('created_at', today())
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($t) {
                return [
                    'name'   => $t->supply->supply_name ?? '—',
                    'detail' => ($t->user->first_name ?? '?') . ' ' . ($t->user->last_name ?? '') .
                                ' · ' . $t->created_at->format('g:i A'),
                    'status' => 'stock_out',
                    'value'  => '-' . $t->quantity,
                    'sub'    => $t->remarks ?? 'No remarks',
                ];
            });

        return response()->json($transactions);
    }
}