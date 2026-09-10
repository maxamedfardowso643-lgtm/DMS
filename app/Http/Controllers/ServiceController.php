<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Models\ActivityLog;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $query = Service::latest();
            $total = Service::count();
            $filtered = $total;
            $services = $query->skip($request->get('start', 0))->take($request->get('length', 10))->get();

            return response()->json([
                'draw' => (int) $request->get('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $filtered,
                'data' => $services->map(fn ($s) => [
                    'id' => $s->id, 'name' => $s->name, 'category' => $s->category,
                    'price' => number_format($s->price, 2), 'duration_minutes' => $s->duration_minutes,
                    'is_active' => $s->is_active,
                ]),
            ]);
        }

        return view('services.index');
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('services.index');
    }

    public function store(StoreServiceRequest $request): JsonResponse
    {
        $service = Service::create($request->validated() + ['is_active' => true]);
        ActivityLog::log('created', "Service {$service->name} added", $service);

        return response()->json(['message' => 'Service added successfully.'], 201);
    }

    public function edit(Service $service): JsonResponse
    {
        return response()->json($service);
    }

    public function update(StoreServiceRequest $request, Service $service): JsonResponse
    {
        $service->update($request->validated());
        ActivityLog::log('updated', "Service {$service->name} updated", $service);

        return response()->json(['message' => 'Service updated successfully.']);
    }

    public function destroy(Service $service): JsonResponse
    {
        $service->delete();
        ActivityLog::log('deleted', "Service {$service->name} deleted", $service);

        return response()->json(['message' => 'Service deleted successfully.']);
    }
}
