<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class UpdateAttendanceController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     */
    public function update(): JsonResponse
    {
        $attendance = Attendance::query()->whereNull('time_end')
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->first();

        if ($attendance) {
            $current_time = now();
            $time_diff = $current_time->diffInSeconds($attendance->time_start);

            // Check to see if it's been an hour since time has been started
            if ($time_diff < 10) {

                return response()->json('early_end');

            } else {
                $attendance->update([
                    'time_end' => now()
                ]);

                return response()->json(gmdate("H:i:s", $attendance->total_time));
            }
        } else {
            $attendances = Attendance::query()
                ->select('time_start')
                ->where('user_id', auth()->id())
                ->orderBy('time_start', 'desc')
                ->first();

            // Current date
            $date = now()->format('d-m-Y');
            $timeStart = $attendances->time_start ? $attendances->time_start->format('d-m-Y'): null;

            // Current time
            $time = now();

            // Start time
            $start_time = "05:30:00 am";
            $new_start_time = Carbon::parse($start_time);

            // End time
            $end_time = "02:30:00 pm";
            $new_end_time = Carbon::parse($end_time);

            // Check to see if time has already been started for the day
            if ($date === $timeStart) {

                return response()->json('already_ended');

            } else if ($time->lt($new_start_time)) {

                return response()->json('early');

            } else if ($time->gt($new_end_time)) {

                return response()->json('late');

            } else {
                auth()->user()->attendanceEntries()->create([
                    'time_start' => now()
                ]);

                return response()->json('Work time has started');
            }
        }
    }
}
