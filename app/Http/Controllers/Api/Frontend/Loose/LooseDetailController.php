<?php

namespace App\Http\Controllers\Api\Frontend\Loose;

use App\Http\Controllers\Api\Frontend\BaseController;
use App\Models\Loose\CreateLoose;
use App\Repositories\Frontend\Loose\LooseRepository;
use Illuminate\Http\Request;
use App\Models\Loose\Loose;

class LooseDetailController extends BaseController
{
    protected $loose;

    /**
    * @param LooseRepository
    * @return void
    */
    public function __construct(LooseRepository $loose)
    {
        $this->loose = $loose;
    }

    /**
     * get loose diamond detail.
    * @param mixed
    * @param Request $request
    * @return json
    */
    public function getLooseDetail($slug)
    {
       
        if (empty($slug) || !is_string( $slug ) || strlen( $slug ) > 100 ) {
            
            return $this->handleError('Invalid Url', 400);
        }

        $sn = array_reverse( explode( '-', $slug ) )[0]; 

        if ( !is_numeric( $sn ) ) {
            
            return $this->handleError('Invalid Url', 400);
        }

        
        // get diamond
        $lot = $this->loose->getDetail($sn);
        
        if (empty($lot)) {
            
            return $this->handleError('product not found', 404);
        }
        
        $lot = $this->getLooseDetailArray($lot);

        return $this->handleResponse($lot, 'success');
    }

    /**
     * get loose diamond detail.
    * @param mixed
    * @param Request $request
    * @return json
    */
    public function getLooseDetailById($id)
    {
        //dd('penciltest');
        if (empty($id) ) {
            
            return $this->handleError('Invalid Url', 400);
        }

        if ( !is_numeric( $id ) ) {
            
            return $this->handleError('Invalid Url', 400);
        }


        // get diamond
        $lot = $this->loose->getDetailById($id);

        if (empty($lot)) {
            
            return $this->handleError('product not found', 404);
        }

        $lot = $this->getLooseDetailArray($lot);

        return $this->handleResponse($lot, 'success');

    }
    
    /**
     * get loose diamond detail.
    * @param mixed
    * @param Request $request
    * @return json
    */
    public function getLooseDetailArray($lot)
    {
        $lot->length = $lot->length . ' mm';
        $lot->width = $lot->width . ' mm';
        $lot->depth = $lot->depth . ' mm';
        $lot->depth_percent = $lot->depth_percent . ' %';
        $lot->table_percent = $lot->table_percent . ' %';
        $lot->weight = $lot->weight . ' ct.';
        $lot->color = ucfirst($lot->color);
        $lot->clarity = strtoupper($lot->clarity);
        $lot->cut_grade = ucfirst($lot->cut_grade);
        $lot->lab = strtoupper($lot->lab);
       
        $lot->title = $lot->weight . ' ' . $lot->shape . ' Lab Grown Diamond';
        $lot->shipping = 1;
        $lot->in_wishlist = 0;
        $lot->in_cart = ($lot) ? 1 : 0;
        $lot->appraisal_price = config('friendlydiamonds.appraisal');
        $lot->quickship_eligibility =  ($lot->country  == 'New York') ? 1 : 0;
        $lot->availability =  1;

        if ( ! empty( $lot->deleted_at ) ) {
            
            $lot->availability =  0;
        } 
        
        if ( $lot->locks_count !== 0 ) {
            
            $lot->availability = 0;
        }
        
        if ($lot->availability == 0 ) {
            
            $lot->price =  '';
            \Log::channel('stack_deleted_diamond')->info([ 'user lands on deleted diamond page' , 'cart id -> ' . request()->cookie('cart-id'), $lot->id, $lot->lot, $lot->availability  ]);
        }

        $lot->description = 'This '.$lot->weight.' Carat '.$lot->shape.' diamond has a '.strtolower($lot->cut_grade).' cut and '.strtoupper($lot->color).' color and '.strtoupper($lot->clarity).' clarity. A '.strtoupper($lot->lab).' grading report comes with the diamond.';
        
        return $lot;
    }
}
