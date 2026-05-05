<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model {
    protected $fillable = ['time', 'activity', 'trainer', 'day_of_week'];
    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}