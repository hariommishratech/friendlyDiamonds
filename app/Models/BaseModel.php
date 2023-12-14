<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

Class BaseModel extends Model
{
    protected static $enableLog = true;

    public static function boot()
    {   
        static::creating(function ($model) {
            
            try {
                
                if( static::$enableLog === false ) {
                    return;
                }
    
                $dirtyAttributes = $model->getDirty();
    
                activity()->performedOn($model)->withProperties($dirtyAttributes)->log('created');

            } catch (\Exception $e) {
                
                \Log::error(['activity log error.', $e->getMessage() ]);
            }
        });

        static::updating(function ($model) {
        
            try {
                
                if( static::$enableLog === false ){
                    return;
                }

                $dirtyAttributes = $model->getDirty();
    
                activity()->performedOn($model)->withProperties($dirtyAttributes)->log('updated');
            
            } catch (\Exception $e) {
                
                \Log::error(['activity log error ', $e->getMessage() ]);
            }

        });

        static::deleting(function ($model) {
            
            try {
                
                if( static::$enableLog === false ){
                    
                    return;
                }
                
                activity()->performedOn($model)->log('deleted');

            } catch (\Exception $e) {
                
                \Log::error(['activity log error ', $e->getMessage() ]);
            }


        });
        
        parent::boot();
    }
    
}