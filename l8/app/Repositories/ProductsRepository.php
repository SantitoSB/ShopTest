<?php


namespace App\Repositories;

use App\Models\Product;
use App\Models\Product as Model;

/**
 * Class ProductsRepository
 * @package App\Repositories
 *
 */
class ProductsRepository extends BaseRepository
{

    /**
     * Function return sorted list of products depends on category id
     *
     * @param $categoryID
     * @param string $orderColumn
     * @param string $order
     * @return mixed
     */
    public function getProductsByCategory($categoryID, $orderColumn = 'id', $order = 'ASC')
    {
        if(!$this->hasColumn($orderColumn))
        {
            $orderColumn = 'id';
            //abort(404);
        }

        return $this->init()->where('category_id', $categoryID)->orderBy($orderColumn, $order)->get();
    }


    /**
     * @inheritDoc
     */
    protected function getModelClass()
    {
        return Model::class;
    }
}
