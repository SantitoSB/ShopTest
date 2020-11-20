<?php


namespace App\Repositories;

use App\Models\category as Model;
use Illuminate\Support\Facades\Log;

class CategoryRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct();
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
