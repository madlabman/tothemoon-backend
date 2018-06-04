<?php

namespace App\Repository;

use App\User;
use Everyman\Neo4j\Cypher\Query;
use Illuminate\Support\Carbon;

class UserRepository extends BaseRepo
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(app()->make(User::class));
    }

//    public function dialogs(User $user)
//    {
//        $queryString = 'MATCH (s:User)-[]-(:Message)-[]-(t:User) WHERE ID(s) = {nodeId} RETURN DISTINCT t;';
//        $query = new Query($this->client, $queryString, [
//            'nodeId' => $user->id,
//        ]);
//        $result = $query->getResultSet();
//
//        return $this->convertResultSet($result);
//    }

    public function users_need_for_accounting()
    {
        $month_ago = Carbon::now()->addMonth(-1)->toDateTimeString();
        $queryString = 'MATCH (t:User) WHERE (t.invested_at < {month_ago} AND t.last_accounted_at IS NULL) 
        OR (t.last_accounted_at < {month_ago} AND t.invested_at <= t.last_accounted_at) RETURN t;';
        $query = new Query($this->client, $queryString, [
            'month_ago' => $month_ago,
        ]);
        $result = $query->getResultSet();

        return $this->convertResultSet($result);
    }

    public function referral_chain($login)
    {
        $queryString = 'MATCH p = (k:User {login:{login}})<-[:HAS_REFERRAL*1..5]-(n:User) RETURN length(p) as c, n.login';
        $query = new Query($this->client, $queryString, [
            'login' => $login,
        ]);

        return $query->getResultSet();
    }
}