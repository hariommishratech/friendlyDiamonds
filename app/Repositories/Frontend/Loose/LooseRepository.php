<?php

namespace App\Repositories\Frontend\Loose;

use App\Repositories\BaseRepository;
use App\Models\Loose\Loose;
use App\Models\Loose\LooseCompare;
use Illuminate\Http\Request;

/**
 * Class LooseRepository.
 */
class LooseRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Loose::class;

    /**
     * get loose dimaond details
     * @param mixed $slug
     */
    public function getDetail($sn)
    {
        return $this->query()
                    ->select('id','lot','sn','slug','country','shape','color','clarity','weight','lab','cut_grade','polish','symmetry','fluorescence','certificate_no','length','width','depth','depth_percent','table_percent','girdle_percent','growth_process','description','certificate_link','diamond_image','diamond_thumb_image','video','price','lw_ratio','meta_title','meta_description','meta_keyword','merchant_id', 'deleted_at','girdle','culet')
                    ->withTrashed()
                    ->where('sn', $sn)
                    //->withCount('locks')
                    //->with('looseInCart')
                    ->first();
    }

    /**
     * get loose dimaond details
     * @param mixed $slug
     */
    public function getDetailById($id)
    {
        return $this->query()
                    ->select('id','lot','sn','slug','country','shape','color','clarity','weight','lab','cut_grade','polish','symmetry','fluorescence','certificate_no','length','width','depth','depth_percent','table_percent','girdle_percent','growth_process','description','certificate_link','diamond_image','diamond_thumb_image','video','price','lw_ratio','meta_title','meta_description','meta_keyword','merchant_id', 'deleted_at','girdle','culet')
                    ->withTrashed()
                    ->where('id', $id)
                    // ->withCount('locks')
                    // ->with('looseInCart')
                    ->first();
    }

    /**
     * get loose dimaond details
     * @param mixed $slug
     * @return mixed
     */
    public function detail($id)
    {
        return $this->query()
            ->withTrashed()
            ->where('id', $id)
            ->orWhere('lot', $id)
            ->withCount('locks')
            ->with('looseInCart')
            ->with('locked', 'assets',
            // 'wishlist'
            )
            ->first();
    }

    /**
    * get loose dimaond details
    * @param mixed $id
    * @return array
    */
    public function getLoosePrice($id)
    {
        return $this->query()->where('id','=',$id)->select(['price'])->value('price');
    }

    /**
    * store loose diamond to Compare
    * @param Request $request
    * @param mixed $cart_id
    * @return array
    */
    public function storeLooseCompare(Request $request)
    {
       if ($request->action === 'remove') {
            return LooseCompare::where('loose_id', $request->item_id)->where('cart_id',  $request->header('cart') )->delete();
        }

        $record = LooseCompare::where('loose_id', $request->item_id)->where('cart_id',  $request->header('cart') )->exists();

        if ( !empty($record) ) {
            return;
        }

        $create = new LooseCompare();
        $create->loose_id = $request->item_id;
        $create->cart_id =  $request->header('cart');
        $create->save();
    }

    /**
    * get diamond detail stored in compare.  
    * @param mixed $cart_id
    * @return array
    */
    public function getCompareItem($cart_id)
    {
        return LooseCompare::where('cart_id', $cart_id)->with('loose')->get();
    }

    /**
    * get diamond detail stored in compare.  
    * @param mixed $cart_id
    * @return array
    */
    public function getComparedCount()
    {
        return LooseCompare::where('cart_id', request()->header('cart'))->count();
    }
}