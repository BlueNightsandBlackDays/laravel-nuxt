<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    public $table = 'attendances';

    protected $fillable = [
        'user_id',
        'time_start',
        'time_end',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'time_start',
        'time_end',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function setTimeStartAttribute($value)
    {
        $this->attributes['time_start'] = $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }

    public function setTimeEndAttribute($value)
    {
        $this->attributes['time_end'] = $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value) : null;
    }

    public function getTotalTimeAttribute()
    {
        $time_start = $this->time_start ? Carbon::createFromFormat('Y-m-d H:i:s', $this->time_start) : null;
        $time_end = $this->time_end ? Carbon::createFromFormat('Y-m-d H:i:s', $this->time_end) : null;

        return $this->time_end ? $time_end->diffInSeconds($time_start) : 0;
    }

    public function getTotalTimeChartAttribute()
    {
        return $this->total_time ? round($this->total_time/3600, 2) : 0;
    }
}
