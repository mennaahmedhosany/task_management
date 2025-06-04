<?php

namespace App\Models;

use App\TaskPriority;
use App\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;


class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected  $fillable  = [
        'title',
        'description',
        'status',
        'due_date',
        'priority',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,

    ];


    //belongs to user
    public function users()
    {
        return $this->belongsToMany(User::class);
    }


    public static function searchTasks(string $query)
    {
        return self::whereRaw(
            "MATCH(title, description) AGAINST(? IN NATURAL LANGUAGE MODE)",
            [$query]
        )->get();
    }
}
