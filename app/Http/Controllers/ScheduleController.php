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
    private const DAY_NAMES = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

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
            'day_mode' => ['nullable', Rule::in(['single', 'range'])],
            'day_of_week' => ['nullable', 'required_if:day_mode,single', 'integer', 'min:0', 'max:6'],
            'day_from' => ['nullable', 'required_if:day_mode,range', 'integer', 'min:0', 'max:6'],
            'day_to' => ['nullable', 'required_if:day_mode,range', 'integer', 'min:0', 'max:6'],
            'start_time' => ['nullable', 'required_if:type,weekly', 'date_format:H:i'],
            'end_time' => ['nullable', 'required_if:type,weekly', 'date_format:H:i', 'after:start_time'],
            'leave_date' => ['nullable', 'required_if:type,leave', 'date'],
            'reason' => ['nullable', 'string'],
        ]);

        if ($data['type'] === 'leave') {
            Schedule::create([
                'dentist_id' => $data['dentist_id'],
                'type' => 'leave',
                'leave_date' => $data['leave_date'],
                'reason' => $data['reason'] ?? null,
                'is_active' => true,
            ]);

            return response()->json(['message' => 'Leave saved successfully.'], 201);
        }

        $days = ($data['day_mode'] ?? 'single') === 'range'
            ? $this->daysBetween((int) $data['day_from'], (int) $data['day_to'])
            : [(int) $data['day_of_week']];

        $saved = [];
        $skipped = [];
        foreach ($days as $day) {
            $overlaps = Schedule::where('dentist_id', $data['dentist_id'])
                ->where('type', 'weekly')
                ->where('day_of_week', $day)
                ->where('start_time', '<', $data['end_time'])
                ->where('end_time', '>', $data['start_time'])
                ->exists();

            if ($overlaps) {
                $skipped[] = self::DAY_NAMES[$day];
                continue;
            }

            Schedule::create([
                'dentist_id' => $data['dentist_id'],
                'type' => 'weekly',
                'day_of_week' => $day,
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'is_active' => true,
            ]);
            $saved[] = self::DAY_NAMES[$day];
        }

        if (! $saved) {
            return response()->json([
                'message' => 'The dentist already has working hours overlapping this time on: ' . implode(', ', $skipped) . '.',
            ], 422);
        }

        $message = 'Schedule saved for ' . implode(', ', $saved) . '.';
        if ($skipped) {
            $message .= ' Skipped (already scheduled): ' . implode(', ', $skipped) . '.';
        }

        return response()->json(['message' => $message], 201);
    }

    /**
     * Days from $from to $to inclusive, wrapping past Saturday (e.g. Sat → Thu = 6,0,1,2,3,4).
     */
    private function daysBetween(int $from, int $to): array
    {
        $days = [$from];
        for ($d = $from; $d !== $to; ) {
            $d = ($d + 1) % 7;
            $days[] = $d;
        }

        return $days;
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
