<?php
/**
 * Created by PhpStorm.
 * User: 刘亚琦
 * Date: 2019/1/03
 * Time: 14:52
 */

namespace App\Http\Controllers\Order;

use App\Model\UserModel;
use Illuminate\Routing\Controller;

class LayouController extends Controller
{
    public function layout(){
        $list = UserModel::all()->toArray();
        $data = [
            'title'     => 'XXXX',
            'list'      => $list
        ];
        return view('lay',$data);
    }
}