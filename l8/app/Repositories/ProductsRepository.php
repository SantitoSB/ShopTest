<?php


namespace App\Repositories;

use App\Models\Product;
use App\Models\Product as Model;
use Illuminate\Support\Facades\Log;

/**
 * Class ProductsRepository
 * @package App\Repositories
 *
 */
class ProductsRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct();

        //Define column for getAll
        $this->columnsGetAll = ['id', 'name', 'price', 'photo', 'category_id'];
    }

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
            Log::warning(' '.__METHOD__.': order column '.$orderColumn.' not found');
        }

        $result = $this->init()->where('category_id', $categoryID)->get();

        return $result;
    }

    /**
     * @param $perPage
     * @param string $orderColumn Possible columns ['id', 'name', 'price', 'photo', 'category_id']
     * @param string $order
     * @return mixed
     *
     * Function returns products with pagination
     *
     */
    public function getAllWithPaginate($perPage, $orderColumn = 'id', $order = 'ASC')
    {
        if(!$this->hasColumn($orderColumn))
        {
            $orderColumn = 'id';
            Log::warning(' '.__METHOD__.': order column '.$orderColumn.' not found');
        }

        return $this->init()->toBase()->select()->orderBy($orderColumn, $order)->paginate($perPage);
    }


    /**
     * @inheritDoc
     */
    protected function getModelClass()
    {
        return Model::class;
    }
}
