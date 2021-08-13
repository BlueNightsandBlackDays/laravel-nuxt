<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
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
            ->orderByDesc('id')
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
            $attendance->update([
                'time_end' => now()
            ]);

            return response()->json(
                'Work time has stopped at ' . gmdate("H:i:s", $attendance->total_time) . ' hours'
            );
        } else {
            $attendances = Attendance::query()
                ->select('time_start')
                ->where('user_id', auth()->id())
                ->orderBy('time_start', 'desc')
                ->first();

            $date = now()->format('d-m-Y');

            $timeStart = $attendances->time_start->format('d-m-Y');
            if ($date === $timeStart) {
                return response()->json('Work already started');
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
