<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Completed_shopping_list extends Model
{
    use HasFactory;
    
    /**
     * 複数代入不可能な属性
     */
    protected $guarded = [];
    
    /**
     * created_atの日付だけ取る
     */
    public function getCreated_atDate()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('Y/m/d');
    }
}
