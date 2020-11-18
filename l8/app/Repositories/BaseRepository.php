<?php

namespace App\Repositories;

/**
 * Class BaseRepository
 * @package App\Repositories
 *
 * Base repository class
 * Get model data. Can't update or create
 *
 */

abstract class BaseRepository
{

    /**
     * @var $model
     *
     */
    protected $model;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * @return mixed
     */
    abstract protected function getModelClass();

    /**
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     *
     * Prepare repository for work
     */
    protected function init()
    {
        return clone $this->model;
    }

    protected function hasColumn($column)
    {
        //check column exist
        $hasColumn = $this->model
            ->getConnection()
            ->getSchemaBuilder()
            ->hasColumn($this->model->getTable(), $column);

        if(!$hasColumn)
        {
            //TODO log error

            return false;
        }

        return true;
    }


}
