<?php

namespace App\Repository;

use App\Message;
use App\User;
use Everyman\Neo4j\Cypher\Query;
use Everyman\Neo4j\Query\ResultSet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MessageRepository extends BaseRepo
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(app()->make(Message::class));
    }

    /**
     * @param ResultSet $resultSet
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function convertResultSet(ResultSet $resultSet){

        $models=[];
        foreach ($resultSet as $row) {
            $node_id = $row['t']->getId();
            $attributes = $row['t']->getProperties();
            $attributes['id'] = $row['t']->getId();
            $model = $this->model->newFromBuilder($attributes);
            $model->loadMissing('fromUser', 'toUser');
            $models[] = $model;
        }
        return Collection::make($models);

    }

    public function chat(User $left, User $right)
    {
        $queryString = "MATCH (a:User)-[]-(t:Message)-[]-(b:User) WHERE a.uuid = {uuid1} AND b.uuid = {uuid2} RETURN t ORDER BY t.created_at DESC;";
        $query = new Query($this->client, $queryString, [
            'uuid1' => $left->uuid,
            'uuid2' => $right->uuid,
        ]);
        $result = $query->getResultSet();

        return $this->convertResultSet($result);
    }

    public function allForUser(User $user)
    {
        $queryString = "MATCH (a:User)-[]-(t:Message)-[]-(b:User) WHERE a.uuid = {uuid1} RETURN t ORDER BY t.created_at DESC;";
        $query = new Query($this->client, $queryString, [
            'uuid1' => $user->uuid,
        ]);
        $result = $query->getResultSet();

        return $this->convertResultSet($result);
    }
}