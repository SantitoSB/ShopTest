<?php

namespace App\Repositories;

/**
 * Class BaseRepository
 * @package App\Repositories
 *
 * Base repository class
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


}
