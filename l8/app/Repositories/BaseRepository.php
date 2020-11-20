<?php

namespace App\Repositories;

use App\Models\category;
use Illuminate\Support\Facades\Log;

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
     * @var model
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

    /**
     * Method return table row by ID
     * @param $id
     * @return mixed
     */
    public function getByID($id)
    {
        //find and check if exist
        $result = $this->init()->whereId($id)->first();

        if(is_null($result))
        {
            Log::error(' '.__METHOD__.': can\'t find category with id = '.$id);
        }

        return $result;
    }

    /**
     * @param string $orderColumn
     * @param string $order
     * @return mixed
     *
     * Get not trashed data from model
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
            ->get();

        return $result;
    }

    public function getConditionalAll($column, $conditional, $value)
    {
        $result = $this->init()
            ->withoutTrashed()
            ->where($column, $conditional, $value)
            ->get()->first();

        return $result;
    }

    /**
     * @param string $orderColumn
     * @param string $order
     * @return mixed
     *
     * Get all not trashed data from model (only for list id, name)
     */
    public function getAllForList($orderColumn = 'id', $order = 'ASC')
    {
        $columns = ['id', 'name'];

        if(!$this->hasColumn($orderColumn) || !in_array($orderColumn, $columns))
        {
            $orderColumn = 'id';
            Log::warning(' '.__METHOD__.': order column '.$orderColumn.' not found');
        }

        $result = $this->init()
            ->toBase()
            ->select($columns)
            ->orderBy($orderColumn, $order)
            ->get();

        return $result;
    }

    /**
     * @param string $orderColumn
     * @param string $order
     * @return mixed
     *
     * Get all trashed data from model
     */
    public function getAllTrashed($orderColumn = 'id', $order = 'ASC')
    {
        if(!$this->hasColumn($orderColumn))
        {
            $orderColumn = 'id';
            Log::warning(' '.__METHOD__.': order column '.$orderColumn.' not found');
        }

        $result = $this->init()
            ->onlyTrashed()
            ->orderBy($orderColumn, $order)
            ->get();


        return $result;
    }

    public function getConditionalTrashed($column, $conditional, $value)
    {
        $result = $this->init()
            ->onlyTrashed()
            ->where($column, $conditional, $value)
            ->get()->first();

        return $result;
    }

    /**
     * @param string $orderColumn
     * @param string $order
     * @return mixed
     *
     * Get all data from model
     */
    public function getAllWithTrashed($orderColumn = 'id', $order = 'ASC')
    {

        if(!$this->hasColumn($orderColumn))
        {
            $orderColumn = 'id';
            Log::warning(' '.__METHOD__.': order column '.$orderColumn.' not found');
        }

        $result = $this->init()
            ->withTrashed()
            ->orderBy($orderColumn, $order)
            ->get();

        return $result;
    }

    /**
     * @param $column
     * @return bool
     *
     * Check column exist
     */
    protected function hasColumn($column)
    {
        //check column exist
        $hasColumn = $this->model
            ->getConnection()
            ->getSchemaBuilder()
            ->hasColumn($this->model->getTable(), $column);

        if(!$hasColumn)
        {
            return false;
        }

        return true;
    }

}
