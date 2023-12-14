<?php

namespace App\Repositories\Frontend\SettingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use App\Models\SettingList\SettingList;
class SettingListRepository extends BaseRepository
{
    const MODEL = SettingList::class;   
    public function __construct()
    {
        
    }    
   
    public function getSettingList($request)
    {
       
        //dd(getMetal($request->Metal));
        $result = $this->query();
        if (!empty($request->Metal)) {            
            $result->where('metal', getMetal($request->Metal));
        }
        // if (!empty($request->Style)) {   
        //     $style =explode(',',$request->Style);         
        //     $result->whereIn('style', $style);
        // }
        
        if(!empty($request->PriceFrom) && !empty($request->PriceTo)){

            $result->whereBetween('price', [ $request->PriceFrom, $request->PriceTo ]);
        }     
        
        
        if (!empty($request->Sort && $request->Sort=="PriceLow")) {            
            $result->orderBy('price', 'asc');
        }elseif(!empty($request->Sort && $request->Sort=="PriceHigh")){
            $result->orderBy('price', 'desc');
        }else{
            $result->orderBy('list_order', 'asc');
        }
       

       
        return $result->paginate(20);
       
        
        
        
    }

    
}
