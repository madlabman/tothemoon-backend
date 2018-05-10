<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;
use Everyman\Neo4j\Query\ResultSet;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Vinelab\NeoEloquent\Eloquent\Model;
use Vinelab\NeoEloquent\Query\Builder;

abstract class BaseRepo
{
    /**
     * The model to execute queries on.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected   $model;

    protected $client;

    /**
     * Create a new repository instance.
     *
     * @param Model $model The model to execute queries on
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->client = DB::connection('neo4j')->getClient();
    }

    /**
     * Get a new instance of the model.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getNew(array $attributes = array())
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * Make a new instance of the entity to query on
     *
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function make(array $with = array())
    {
        return $this->model->with($with);
    }

    /**
     * Retrieve all entities
     *
     * @param array $with
     * @return Collection
     */
    public function all(array $with = array())
    {
        $entity = $this->make($with);
        return $entity->get();
    }

    /**
     * Find a single entity
     *
     *  - MemberOption::scopeCompositeKey
     *
     * @param $id
     * @param array $with
     * @return Model
     */
    public function find($id, array $with = array())
    {
        $entity = $this->make($with);

        if (is_array($id)) {
            $model = $entity->compositeKey($id)->first();
        } else {
            $model = $this->model->find($id);
        }

        return $model;
    }

    /**
     * Search for many results by key and value
     *
     * @param string $key
     * @param mixed $value
     * @param array $with
     * @return Builder
     */
    public function getBy($key, $value, array $with = array())
    {
        return $this->make($with)->where($key, '=', $value)->get();
    }

    public function create(array $input)
    {
        try {
            return $this->model->create(
                $input
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update($uuid, array $input)
    {
        $model = $this->findByUuid($uuid);
        if($model->update($input)){
            return $model;
        };
        App::abort(500, 'Something bad happened');
    }

    public function delete($uuid)
    {
        return $this->findByUuid($uuid)->delete();
    }

    public function findByUuid($uuid, array $with = array())
    {
        return $this->model->where(array('uuid'=>$uuid))->with($with)->firstOrFail();
    }

    /**
     * @param ResultSet $resultSet
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function convertResultSet(ResultSet $resultSet){

        $models=[];
        foreach ($resultSet as $row) {
            $attributes = $row['t']->getProperties();
            $attributes['id'] = $row['t']->getId();
            $model = $this->model->newFromBuilder($attributes);
            $model->setConnection(DB::connection('neo4j'));
            $models[] = $model;
        }
        return Collection::make($models);

    }
}