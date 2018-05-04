<?php

namespace App\Repository;

use App\User;
use Everyman\Neo4j\Cypher\Query;

class UserRepository extends BaseRepo
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(app()->make(User::class));
    }

    public function one()
    {
        $queryString = 'MATCH (t:AppUser)-[:HAS_BALANCE]->(b:AppBalance {body:0}) RETURN t';
        $query = new Query($this->client, $queryString);
        $result = $query->getResultSet();

        return $this->convertResultSet($result);
    }
}