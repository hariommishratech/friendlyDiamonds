<?php

namespace App\Models\Loose;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LooseCompare extends BaseModel
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loose_compare';
    protected static $enableLog = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loose()
    {
        return $this->hasOne(Loose::class, 'id', 'loose_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stoneMedia()
    {
        return $this->hasOne(LooseMedia::class, 'loose_id', 'loose_id');
    }
}
