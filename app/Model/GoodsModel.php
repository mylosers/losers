<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsModel extends Model
{
    public $table='shop_goods';
    public $timestamps = false;
    public $primaryKey = 'goods_id';


}
