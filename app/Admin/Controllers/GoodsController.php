<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Show;

use App\Model\GoodsModel;


/**
 * 商品控制器
 * Class GoodsController
 * @package App\Admin\Controllers
 */
class GoodsController extends Controller
{
    use HasResourceActions;
    //首页方法

    public function index(Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('商品列表')
            ->body($this->grid());
    }

    //首页视图
    protected function grid()
    {
        $grid = new Grid(new GoodsModel());

        $grid->model()->orderBy('goods_id','desc');     //倒序排序

        $grid->goods_id('商品ID');
        $grid->goods_name('商品名称');
        $grid->goods_stock('库存');
        $grid->goods_selfprice('价格');
        $grid->create_at('添加时间');
        $grid->add_time('时𨰻间')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });

        return $grid;
    }

    //新增方法
    public function create(Content $content){
        return $content
            ->header('商品管理')
            ->description('添加')
            ->body($this->form());
    }

    //编辑方法
    public function edit($id, Content $content)
    {

        return $content
            ->header('商品管理')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }

    //编辑视图
    public function form(){
        $form = new Form(new GoodsModel());

        $form->display('goods_id', '商品ID');
        $form->text('goods_name', '商品名称');
        $form->number('goods_stock', '库存');
        $form->currency('goods_selfprice', '价格')->symbol('¥');
        $form->ckeditor('content');

        return $form;
    }

    //删除
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    protected function detail($id)
    {
        $show = new Show(GoodsModel::findOrFail($id));

        $show->goods_id('Goods id');
        $show->goods_name('Goods name');
        $show->cate_id('Cate id');
        $show->goods_selfprice('Goods selfprice');
        $show->goods_stock('Goods stock');
        $show->status('Status');
        $show->ctime('Ctime');
        $show->utime('Utime');

        return $show;
    }

}