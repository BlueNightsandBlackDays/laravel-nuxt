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
        if(auth()->user()->getAuthIdentifier() == $id){
            $attendance = Attendance::query()->where('user_id', $id)->orderByDesc('id')->simplePaginate(31);

            return response()->json($attendance);
        }
        return response()->json('Unauthorized');
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
