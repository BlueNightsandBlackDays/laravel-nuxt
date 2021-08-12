<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Attendance;

class ViewAttendanceController extends Controller
{
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
     * Display the specified resource.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function showChart(int $id): JsonResponse
    {
        if(auth()->user()->isAdmin()){
            $attendance = Attendance::query()
                ->select('time_start')
                ->where('user_id', $id)
                ->orderByDesc('id')
                ->limit(31)->get();

            $data = [];

            foreach($attendance as $row) {
                $data[] = $row->time_start;
            }

            return response()->json($data);
        }
        return response()->json('Unauthorized');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if(auth()->user()->isAdmin()) {
            Attendance::query()->where('id', $id)->delete();
            return response()->json('deleted');
        }
        return response()->json('Unauthorized');
    }
}
