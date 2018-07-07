<?php
/**
 * Created by PhpStorm.
 * User: che
 * Date: 07.07.2018
 * Time: 17:19
 */

namespace App\Exceptions;

use Exception;

class NotEnoughMoneyToWithdraw extends Exception
{
    private $max_btc;

    private $max_usd;

    /**
     * @return mixed
     */
    public function getMaxBtc()
    {
        return $this->max_btc;
    }

    /**
     * @param mixed $max_btc
     */
    public function setMaxBtc($max_btc): void
    {
        $this->max_btc = $max_btc;
    }

    /**
     * @return mixed
     */
    public function getMaxUsd()
    {
        return $this->max_usd;
    }

    /**
     * @param mixed $max_usd
     */
    public function setMaxUsd($max_usd): void
    {
        $this->max_usd = $max_usd;
    }


}