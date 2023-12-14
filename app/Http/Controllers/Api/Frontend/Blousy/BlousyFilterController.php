<?php

namespace App\Http\Controllers\Api\Frontend\Blousy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Frontend\BaseController as BaseController;
use App\Repositories\Frontend\Blousy\BlousyFilterRepository;

class BlousyFilterController extends BaseController
{
    protected $filter;
    public function __construct(BlousyFilterRepository $filter)
    {
             $this->filter = $filter;
    }
    public function getBlousyFilter(Request $request)
    {        
        $data = $this->filter->getLoose($request);
       return $this->handleResponse( $data , 'success');
    }
}
