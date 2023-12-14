<?php

namespace App\Models\Loose;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
class LockedLooseLots extends BaseModel
{
    use HasFactory, Searchable,SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'locked_loose_lots';

    protected static $enableLog = false;
    /**
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'item_id',
        'lock_type',
        'user_id',
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'item_id',
        'lock_type',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loose()
    {
        return $this->belongsTo(Loose::class, 'item_id', 'id');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

