<?php

namespace App\Admin\Controllers;

use App\Model\GoodsModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GoodController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GoodsModel);

        $grid->goods_id('Goods id');
        $grid->goods_name('Goods name');
        $grid->cate_id('Cate id');
        $grid->goods_selfprice('Goods selfprice');
        $grid->goods_stock('Goods stock');
        $grid->status('Status');
        $grid->ctime('Ctime');
        $grid->utime('Utime');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
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

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GoodsModel);

        $form->text('goods_name', 'Goods name');
        $form->number('cate_id', 'Cate id');
        $form->decimal('goods_selfprice', 'Goods selfprice');
        $form->number('goods_stock', 'Goods stock');
        $form->switch('status', 'Status');
        $form->number('ctime', 'Ctime');
        $form->number('utime', 'Utime');

        return $form;
    }
}
