<?php


namespace App\Repositories;

use App\Models\category as Model;


class CategoryRepository extends BaseRepository
{

    /**
     * @param $columns
     * @return mixed
     */

    public function getAll($columns)
    {
        dd($this->init()->toBase()->getOnly($columns));
        return $this->init()->all($columns)->toBase()->getOnly($columns);
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
