<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;

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
        $attendance = Attendance::query()->orderByDesc('id')->simplePaginate(10);
/*        $attendance = Attendance::query()->addSelect(['name' => User::query()->select('name')
            ->whereColumn('id', 'user.id')
        ])->orderByDesc('id')->simplePaginate(10)->get();*/
        return response()->json($attendance);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreAttendanceRequest  $request
     * @return JsonResponse
     */
    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        $attendance = Attendance::query()->create($request->all());

        return response()->json($attendance);
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
     * @param  UpdateAttendanceRequest  $request
     * @param  Attendance $attendance
     * @return bool
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance): bool
    {
        return $attendance->update($request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     */
    public function updateCurrent(): JsonResponse
    {
        $attendance = Attendance::query()->whereNull('time_end')
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->first();

        if ($attendance)
        {
            $attendance->update([
                'time_end' => now()
            ]);

            return response()->json(
                'Work time has stopped at ' . gmdate("H:i:s", $attendance->total_time) . ' hours'
            );
        } else {
            auth()->user()->attendanceEntries()->create([
                'time_start' => now()
            ]);

            return response()->json(
                'Work time has started'
            );
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
