<?php

namespace App\Http\Controllers\Api\Frontend\SettingList;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Frontend\BaseController as BaseController;
use App\Repositories\Frontend\SettingList\SettingListRepository;

class SettingListController extends BaseController
{
    protected $settingList;
    public function __construct(SettingListRepository $settingList)
    {
             $this->settingList = $settingList;
    }
    public function getSetting(Request $request)
    {        
        $data = $this->settingList->getSettingList($request);
       return $this->handleResponse( $data , 'success');
    }
}
