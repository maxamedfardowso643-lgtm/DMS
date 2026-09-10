<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\InventoryItem;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $items = InventoryItem::latest()->get();

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $items->count(),
                'recordsFiltered' => $items->count(),
                'data' => $items->map(fn ($i) => [
                    'id' => $i->id, 'item_code' => $i->item_code, 'name' => $i->name, 'unit' => $i->unit,
                    'quantity_on_hand' => $i->quantity_on_hand, 'reorder_level' => $i->reorder_level,
                    'unit_cost' => number_format($i->unit_cost, 2), 'low_stock' => $i->isLowStock(),
                ]),
            ]);
        }

        return view('inventory.index');
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('inventory.index');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'unit' => ['nullable', 'string', 'max:30'],
            'quantity_on_hand' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:150'],
        ]);

        $last = InventoryItem::withTrashed()->orderByDesc('item_code')->value('item_code');
        $next = $last ? ((int) substr($last, -4)) + 1 : 1;
        $data['item_code'] = 'INV-' . str_pad($next, 4, '0', STR_PAD_LEFT);
        $item = InventoryItem::create($data + ['is_active' => true]);

        ActivityLog::log('created', "Inventory item {$item->name} added", $item);

        return response()->json(['message' => 'Item added successfully.'], 201);
    }

    public function edit(InventoryItem $inventory): JsonResponse
    {
        return response()->json($inventory);
    }

    public function update(Request $request, InventoryItem $inventory): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'unit' => ['nullable', 'string', 'max:30'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:150'],
        ]);

        $inventory->update($data);
        ActivityLog::log('updated', "Inventory item {$inventory->name} updated", $inventory);

        return response()->json(['message' => 'Item updated successfully.']);
    }

    public function destroy(InventoryItem $inventory): JsonResponse
    {
        $inventory->delete();
        ActivityLog::log('deleted', "Inventory item {$inventory->name} deleted", $inventory);

        return response()->json(['message' => 'Item deleted successfully.']);
    }

    public function stockMovement(Request $request, InventoryItem $inventory): JsonResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['in', 'out'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string', 'max:150'],
        ]);

        DB::transaction(function () use ($data, $inventory) {
            StockMovement::create($data + ['inventory_item_id' => $inventory->id, 'performed_by' => Auth::id()]);

            $delta = $data['type'] === 'in' ? $data['quantity'] : -$data['quantity'];
            $inventory->increment('quantity_on_hand', $delta);
        });

        return response()->json(['message' => 'Stock movement recorded.', 'quantity_on_hand' => $inventory->fresh()->quantity_on_hand]);
    }
}
