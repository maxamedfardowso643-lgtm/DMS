<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $dentists = Dentist::with(['user', 'schedules'])->get();

        return view('schedules.index', compact('dentists'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('schedules.index');
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'dentist_id' => ['required', 'exists:dentists,id'],
            'type' => ['required', Rule::in(['weekly', 'leave'])],
            'day_of_week' => ['nullable', 'integer', 'min:0', 'max:6'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'leave_date' => ['nullable', 'date'],
            'reason' => ['nullable', 'string'],
        ]);

        Schedule::create($data + ['is_active' => true]);

        return response()->json(['message' => 'Schedule saved successfully.'], 201);
    }

    public function edit(Schedule $schedule): JsonResponse
    {
        return response()->json($schedule);
    }

    public function update(Request $request, Schedule $schedule): JsonResponse
    {
        $data = $request->validate([
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $schedule->update($data);

        return response()->json(['message' => 'Schedule updated successfully.']);
    }

    public function destroy(Schedule $schedule): JsonResponse
    {
        $schedule->delete();

        return response()->json(['message' => 'Schedule removed successfully.']);
    }
}
