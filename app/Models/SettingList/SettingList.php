<?php

namespace App\Models\SettingList;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SettingList extends BaseModel
{
    use HasFactory;
    protected $table = 'ring_settings';
}
