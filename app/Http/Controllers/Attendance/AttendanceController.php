<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
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

        return response()->json($attendance);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $users = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return response()->json($users);
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
     * @return JsonResponse
     */
    public function showCurrent(): JsonResponse
    {
        $attendance = Attendance::query()->whereNull('time_end')
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->first();

        return response()->json($attendance);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $attendance = Attendance::query()->where('user_id', $id)->orderByDesc('id')->simplePaginate(31);

        return response()->json($attendance);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Attendance $attendance
     * @return JsonResponse
     */
    public function edit(Attendance $attendance): JsonResponse
    {
        $users = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $attendance->load('user');
        return response()->json(compact($users, $attendance));
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
            auth()->user()->timeEntries()->create([
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
     * @param Attendance $attendance
     * @return JsonResponse
     */
    public function destroy(Attendance $attendance): JsonResponse
    {
        $attendance->delete();
        return response()->json('deleted');
    }
}
