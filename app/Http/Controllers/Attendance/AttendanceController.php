<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Attendance::class, 'attendances');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        //$attendance = Attendance::query()->orderByDesc('id')->simplePaginate(10);
        $attendance = DB::table('attendances')
            ->join('users', 'users.id', '=', 'attendances.user_id')
            ->select('attendances.*', 'users.first_name', 'users.middle_name')
            ->orderByDesc('created_at')
            ->simplePaginate(10);

        return response()->json($attendance);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     */
    public function updateAttendance(): JsonResponse
    {
        $attendance = Attendance::query()->whereNull('time_end')
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->first();

        if ($attendance) {
            $time_start = Attendance::query()->whereNull('time_end')
                ->select('time_start')
                ->where('user_id', auth()->id())
                ->first();

            $current_time = now();
            $time_diff = $current_time->diffInSeconds($time_start->time_start);

            // Check to see if it's been an hour since time has been started
            if ($time_diff < 60) {

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
            $timeStart = $attendances->time_start->format('d-m-Y');

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

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy()
    {
        //
    }
}
