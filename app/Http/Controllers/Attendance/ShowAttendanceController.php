<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Attendance;

class ShowAttendanceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function showChart(int $id): JsonResponse
    {
        if (auth()->user()->isAdmin()) {
            $attendance = Attendance::query()
                ->select('time_start')
                ->where('user_id', $id)
                ->orderByDesc('created_at')
                ->limit(31)->get();

            $data = [];

            foreach($attendance as $row) {
                $data[] = $row->time_start;
            }

            return response()->json($data);
        }
        return response()->json('Unauthorized');
    }
}
