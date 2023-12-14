<?php

namespace App\Repositories\Frontend\Blousy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use App\Models\Blousy\Blousy;
class BlousyFilterRepository extends BaseRepository
{
    const MODEL = Blousy::class;   
    public function __construct()
    {
        
    }    
   
    public function getLoose($request)
    {
        $result = $this->query();
        
        $result->select(
            '*',
            DB::raw('CASE WHEN weight > 1 THEN price * 1.33 ELSE price * 0.67 END as price')
        );
        if (!empty($request->Shape)) {
            $shape =explode(',',$request->Shape);
            $result->whereIn('shape', $shape);
        }

        if (!empty($request->Color)) {
            $color =explode(',',$request->Color);
            $result->whereIn('color', $color);
        }

        if (!empty($request->Clarity)) {
            $clarity =explode(',',$request->Clarity);
            $result->whereIn('clarity', $clarity);
        }
        
        if(!empty($request->CaratFrom) && !empty($request->CaratTo)){

            $result->whereBetween('weight', [ $request->CaratFrom, $request->CaratTo ]);
        }

        return $result->paginate(20);
       
        
        
        
    }

    
}
