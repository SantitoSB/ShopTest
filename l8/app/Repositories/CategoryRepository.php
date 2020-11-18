<?php


namespace App\Repositories;

use App\Models\category as Model;


class CategoryRepository extends BaseRepository
{
    /**
     * @param string $orderColumn
     * @param string $order
     * @return mixed
     *
     * Get all data from categories model
     */
    public function getAll($orderColumn = 'id', $order = 'ASC')
    {
        if(!$this->hasColumn($orderColumn))
        {
            $orderColumn = 'id';
            //abort(404);
        }

        return $this->init()->toBase()->orderBy($orderColumn, $order)->get();
    }

    /**
     * Method return table row by ID
     * @param $id
     * @return mixed
     */
    public function getByID($id)
    {
        //find and check if exist
        $category = $this->init()->whereId($id)->get();

        if(empty($category))
        {
            //TODO log error
            return null;
            //abort(404);
        }

        return $category;
    }


    /**
     * @inheritDoc
     *
     * Return current model class
     */
    protected function getModelClass()
    {
        return Model::class;
    }


}
