<?php


namespace App\Repositories;

use App\Models\Product;
use App\Models\Product as Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Psy\Util\Str;

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
     * @return LengthAwarePaginator
     *
     * Function returns products with pagination
     *
     */
    public function getAllWithPaginate($perPage, $orderColumn = 'id', $order = 'ASC')
    {
        $columns = ['id','name','description','price','photo','category_id'];

        if(!$this->hasColumn($orderColumn))
        {
            $orderColumn = 'id';
            Log::warning(' '.__METHOD__.': order column '.$orderColumn.' not found');
        }

        if(!in_array($orderColumn, $columns))
        {
            $orderColumn = 'id';
            Log::warning(' '.__METHOD__.': order column '.$orderColumn.' not in send list');
        }

        $result = $this->init()
            //->toBase()
            ->select($columns)
            ->orderBy($orderColumn, $order)
            ->with(['category:id,name'])
            //->with(['category'=>function($query){$query->select(['id', 'name']);}])
            ->paginate($perPage);

        return $result;
    }

    /**
     * @param string $orderColumn
     * @param string $order
     * @return mixed
     */
    public function getAll($orderColumn = 'id', $order = 'ASC')
    {
        if(!$this->hasColumn($orderColumn))
        {
            $orderColumn = 'id';
            Log::warning(' '.__METHOD__.': order column '.$orderColumn.' not found');
        }

        $result = $this->init()
            ->select()
            ->orderBy($orderColumn, $order)
            ->with('category:id,name')
            ->get();

        return $result;
    }


    /**
     * @inheritDoc
     */
    protected function getModelClass()
    {
        return Model::class;
    }
}
