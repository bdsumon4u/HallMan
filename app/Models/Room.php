<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'room_no', 'number');
    }

    public function status(): Attribute
    {
        return Attribute::get(function () {
            if ($this->capacity > $this->students_count) {
                return ($this->capacity - $this->students_count) . ' Left';
            }
            
            return 'Full';
        });
    }
}
