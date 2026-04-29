<?php

namespace App\Http\Controllers;

use App\Models\Barn;
use App\Models\BarnStaff;
use App\Models\BarnSupply;
use App\Models\Category;
use App\Models\InventoryTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private function getOwnerBarn()
    {
        return Barn::where('barn_owner_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $barn = $this->getOwnerBarn();

        $categories = Category::orderBy('category_name')->get();
        $supplies   = BarnSupply::where('barn_id', $barn->id)
                        ->orderBy('supply_name')
                        ->get();

        $staffUsers = BarnStaff::with('user')
            ->where('barn_id', $barn->id)
            ->get()
            ->map(fn($s) => [
                'id'   => $s->user_id,
                'name' => $s->user->first_name . ' ' . $s->user->last_name
            ]);

        return view('barn_owner_reports.index', compact('barn', 'categories', 'supplies', 'staffUsers'));
    }

    public function generate(Request $request)
    {
        $barn = $this->getOwnerBarn();
        $type = $request->input('report_type');

        return match ($type) {
            'stock_movement'    => $this->stockMovement($request, $barn),
            'current_inventory' => $this->currentInventory($request, $barn),
            'low_stock'         => $this->lowStock($request, $barn),
            'valuation'         => $this->valuation($request, $barn),
            'usage_analysis'    => $this->usageAnalysis($request, $barn),
            'barn_summary'      => $this->barnSummary($request, $barn),
            default             => response()->json(['rows' => [], 'summary' => []]),
        };
    }

    // ── Stock Movement ────────────────────────────────────────────────────────
    private function stockMovement(Request $request, Barn $barn)
    {
        $query = InventoryTransaction::with(['supply.category', 'user', 'supplier'])
            ->where('barn_id', $barn->id);

        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->filled('txn_type'))  $query->where('transaction_type', $request->txn_type);
        if ($request->filled('category'))  $query->whereHas('supply', fn($q) => $q->where('category_id', $request->category));
        if ($request->filled('supply'))    $query->where('supply_id', $request->supply);
        if ($request->filled('user'))      $query->where('user_id', $request->user);

        $rows = $query->orderByDesc('created_at')->get()->map(function ($t) {
            // Resolve supplier name safely — relationship may not be defined on older records
            $supplierName = '—';
            if ($t->supplier_id && $t->relationLoaded('supplier') && $t->supplier) {
                $supplierName = $t->supplier->supplier_name
                    ?? $t->supplier->name
                    ?? '—';
            }

            return [
                'date'      => Carbon::parse($t->created_at)->format('M j, Y g:i A'),
                'supply'    => $t->supply->supply_name ?? '—',
                'category'  => $t->supply->category->category_name ?? '—',
                'type'      => $t->transaction_type,
                'quantity'  => $t->quantity,
                'unit_cost' => $t->unit_cost,           // raw numeric for blade formatter
                'staff'     => $t->user ? $t->user->first_name . ' ' . $t->user->last_name : '—',
                'supplier'  => $supplierName,
                'remarks'   => $t->remarks ?? '—',
                // NOTE: 'balance' reflects current stock, not a running balance at
                // transaction time. A true running balance requires replaying all
                // transactions chronologically per supply — add if needed later.
                'balance'   => $t->supply->stock ?? '—',
            ];
        });

        $totalIn        = $rows->where('type', 'stock_in')->sum('quantity');
        $totalOut       = $rows->where('type', 'stock_out')->sum('quantity');
        $totalStockCost = $rows
            ->where('type', 'stock_in')
            ->filter(fn($r) => $r['unit_cost'] !== null)
            ->sum(fn($r) => (float) $r['unit_cost'] * $r['quantity']);

        return response()->json([
            'rows'   => $rows,
            'totals' => [
                'date'      => '',
                'supply'    => '',
                'category'  => '',
                'type'      => '',
                'quantity'  => ($totalIn - $totalOut) . ' net',
                'unit_cost' => '',
                'staff'     => '',
                'supplier'  => '',
                'remarks'   => '',
                'balance'   => '',
            ],
            'summary' => [
                'Total Transactions' => $rows->count(),
                'Total Stock In'     => $totalIn,
                'Total Stock Out'    => $totalOut,
                'Net Movement'       => $totalIn - $totalOut,
                'Total Cost (In)'    => '₱' . number_format($totalStockCost, 2),
            ],
        ]);
    }

    // ── Current Inventory ─────────────────────────────────────────────────────
    private function currentInventory(Request $request, Barn $barn)
    {
        $query = BarnSupply::with('category')
            ->where('barn_id', $barn->id)
            ->where('supply_status', 'active');

        if ($request->filled('category')) $query->where('category_id', $request->category);

        if ($request->filled('stock_status')) {
            match ($request->stock_status) {
                'in_stock'     => $query->whereColumn('stock', '>', 'reorder_level'),
                'low_stock'    => $query->whereColumn('stock', '<=', 'reorder_level')->where('stock', '>', 0),
                'out_of_stock' => $query->where('stock', 0),
                default        => null,
            };
        }

        $rows = $query->orderBy('supply_name')->get()->map(function ($s) {
            $isOut = $s->stock === 0;
            $isLow = $s->stock <= $s->reorder_level;
            return [
                'supply'        => $s->supply_name,
                'category'      => $s->category->category_name ?? '—',
                'stock'         => $s->stock,
                'reorder_level' => $s->reorder_level,
                'status'        => $isOut ? 'Out of Stock' : ($isLow ? 'Low Stock' : 'In Stock'),
            ];
        });

        $totalStock = $rows->sum('stock');
        $lowCount   = $rows->where('status', 'Low Stock')->count();
        $outCount   = $rows->where('status', 'Out of Stock')->count();

        return response()->json([
            'rows'   => $rows,
            'totals' => [
                'supply'        => '',
                'category'      => '',
                'stock'         => $totalStock,
                'reorder_level' => '',
                'status'        => '',
            ],
            'summary' => [
                'Total Supplies'  => $rows->count(),
                'Total Stock'     => $totalStock,
                'Low Stock Items' => $lowCount,
                'Out of Stock'    => $outCount,
            ],
        ]);
    }

    // ── Low Stock / Reorder Alert ─────────────────────────────────────────────
    private function lowStock(Request $request, Barn $barn)
    {
        $query = BarnSupply::with('category')
            ->where('barn_id', $barn->id)
            ->where('supply_status', 'active')
            ->whereColumn('stock', '<=', 'reorder_level');

        if ($request->filled('category')) $query->where('category_id', $request->category);
        if ($request->filled('criticality') && $request->criticality === 'critical') {
            $query->whereRaw('stock < (reorder_level * 0.5)');
        }

        $rows = $query->orderBy('stock')->get()->map(function ($s) {
            $shortage   = $s->reorder_level - $s->stock;
            $isCritical = $s->stock < ($s->reorder_level * 0.5);
            return [
                'supply'        => $s->supply_name,
                'category'      => $s->category->category_name ?? '—',
                'stock'         => $s->stock,
                'reorder_level' => $s->reorder_level,
                'shortage'      => max(0, $shortage),
                'criticality'   => $isCritical ? 'Critical' : 'Low Stock',
            ];
        });

        return response()->json([
            'rows'   => $rows,
            'totals' => [
                'supply'        => '',
                'category'      => '',
                'stock'         => '',
                'reorder_level' => '',
                'shortage'      => $rows->sum('shortage'),
                'criticality'   => '',
            ],
            'summary' => [
                'Items to Restock'   => $rows->count(),
                'Critical Items'     => $rows->where('criticality', 'Critical')->count(),
                'Total Units Needed' => $rows->sum('shortage'),
            ],
        ]);
    }

    // ── Inventory Valuation ───────────────────────────────────────────────────
    // Correct approach: weighted average cost derived from actual stock_in
    // transactions (sum of unit_cost × quantity) ÷ total quantity received.
    // This reflects what was actually paid, not a stale field on BarnSupply.
    private function valuation(Request $request, Barn $barn)
    {
        $query = BarnSupply::with('category')
            ->where('barn_id', $barn->id)
            ->where('supply_status', 'active');

        if ($request->filled('category')) $query->where('category_id', $request->category);

        // Pre-fetch all stock_in transactions for this barn grouped by supply_id
        // so we can compute weighted average cost without N+1 queries.
        $txnsBySuppply = InventoryTransaction::where('barn_id', $barn->id)
            ->where('transaction_type', 'stock_in')
            ->whereNotNull('unit_cost')
            ->get()
            ->groupBy('supply_id');

        $rows = $query->orderBy('supply_name')->get()->map(function ($s) use ($txnsBySuppply) {
            $txns         = $txnsBySuppply->get($s->id, collect());
            $totalQtyIn   = $txns->sum('quantity');
            $totalCostIn  = $txns->sum(fn($t) => (float) $t->unit_cost * $t->quantity);

            // Weighted average cost; fall back to 0 if no costed transactions exist
            $avgCost   = $totalQtyIn > 0 ? $totalCostIn / $totalQtyIn : 0;
            $stockValue = $s->stock * $avgCost;

            return [
                'supply'          => $s->supply_name,
                'category'        => $s->category->category_name ?? '—',
                'stock'           => $s->stock,
                'avg_unit_cost'   => $avgCost,           // raw float — blade formats with ₱
                'total_value'     => $stockValue,        // raw float — blade formats with ₱
            ];
        });

        $grandTotal = $rows->sum('total_value');

        return response()->json([
            'rows'   => $rows,
            'totals' => [
                'supply'        => '',
                'category'      => '',
                'stock'         => $rows->sum('stock'),
                'avg_unit_cost' => '',
                'total_value'   => $grandTotal,          // raw float for blade formatter
            ],
            'summary' => [
                'Total Supplies'    => $rows->count(),
                'Total Stock Units' => $rows->sum('stock'),
                'Grand Total Value' => '₱' . number_format($grandTotal, 2),
            ],
        ]);
    }

    // ── Usage / Consumption Analysis ──────────────────────────────────────────
    private function usageAnalysis(Request $request, Barn $barn)
    {
        $from = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $to   = $request->filled('date_to')   ? Carbon::parse($request->date_to)->endOfDay()     : Carbon::now()->endOfDay();
        $days = max(1, $from->diffInDays($to));

        $query = InventoryTransaction::with(['supply.category'])
            ->where('barn_id', $barn->id)
            ->where('transaction_type', 'stock_out')
            ->whereBetween('created_at', [$from, $to]);

        if ($request->filled('category')) $query->whereHas('supply', fn($q) => $q->where('category_id', $request->category));
        if ($request->filled('supply'))   $query->where('supply_id', $request->supply);

        $grouped = $query->get()->groupBy('supply_id');

        $rows = $grouped->map(function ($txns) use ($days) {
            $supply     = $txns->first()->supply;
            $total      = $txns->sum('quantity');
            $lastUsed   = Carbon::parse($txns->max('created_at'))->format('M j, Y');
            $activeDays = $txns->groupBy(fn($t) => Carbon::parse($t->created_at)->toDateString())->count();

            return [
                'supply'      => $supply->supply_name ?? '—',
                'category'    => $supply->category->category_name ?? '—',
                'total_used'  => $total,
                'avg_daily'   => round($total / $days, 2),
                'days_active' => $activeDays,
                'last_used'   => $lastUsed,
            ];
        })
        ->sortByDesc('total_used')
        ->values();

        return response()->json([
            'rows'   => $rows,
            'totals' => [
                'supply'      => '',
                'category'    => '',
                'total_used'  => $rows->sum('total_used'),
                'avg_daily'   => round($rows->sum('avg_daily'), 2),
                'days_active' => '',
                'last_used'   => '',
            ],
            'summary' => [
                'Period (Days)'    => $days,
                'Supplies Tracked' => $rows->count(),
                'Total Consumed'   => $rows->sum('total_used'),
                'Avg Daily Usage'  => round($rows->avg('avg_daily') ?? 0, 2),
            ],
        ]);
    }

    // ── Barn Summary ──────────────────────────────────────────────────────────
    private function barnSummary(Request $request, Barn $barn)
    {
        $suppliesQuery = BarnSupply::where('barn_id', $barn->id)->where('supply_status', 'active');
        if ($request->filled('category')) $suppliesQuery->where('category_id', $request->category);

        $supplies   = $suppliesQuery->get();
        $staffCount = BarnStaff::where('barn_id', $barn->id)->where('staff_status', 'active')->count();
        $lowCount   = $supplies->filter(fn($s) => $s->stock <= $s->reorder_level && $s->stock > 0)->count();
        $outCount   = $supplies->where('stock', 0)->count();

        // Compute inventory value using weighted average cost from transactions
        $txnsBySuppply = InventoryTransaction::where('barn_id', $barn->id)
            ->where('transaction_type', 'stock_in')
            ->whereNotNull('unit_cost')
            ->get()
            ->groupBy('supply_id');

        $totalValue = $supplies->sum(function ($s) use ($txnsBySuppply) {
            $txns        = $txnsBySuppply->get($s->id, collect());
            $totalQtyIn  = $txns->sum('quantity');
            $totalCostIn = $txns->sum(fn($t) => (float) $t->unit_cost * $t->quantity);
            $avgCost     = $totalQtyIn > 0 ? $totalCostIn / $totalQtyIn : 0;
            return $s->stock * $avgCost;
        });

        $rows = collect([
            ['metric' => 'Barn Name',            'value' => $barn->barn_name],
            ['metric' => 'Farm Type',            'value' => $barn->farm_type],
            ['metric' => 'Location',             'value' => "{$barn->city}, {$barn->region}"],
            ['metric' => 'Total Supply Types',   'value' => $supplies->count()],
            ['metric' => 'Total Stock Units',    'value' => $supplies->sum('stock')],
            ['metric' => 'Inventory Value',      'value' => '₱' . number_format($totalValue, 2)],
            ['metric' => 'Low Stock Items',      'value' => $lowCount],
            ['metric' => 'Out of Stock Items',   'value' => $outCount],
            ['metric' => 'Active Barn Staff',    'value' => $staffCount],
            ['metric' => 'Report Generated',     'value' => Carbon::now()->format('M j, Y g:i A')],
        ]);

        return response()->json([
            'rows'    => $rows,
            'summary' => [
                'Total Supplies'  => $supplies->count(),
                'Total Stock'     => $supplies->sum('stock'),
                'Inventory Value' => '₱' . number_format($totalValue, 2),
                'Low Stock'       => $lowCount,
                'Out of Stock'    => $outCount,
                'Active Staff'    => $staffCount,
            ],
        ]);
    }
}