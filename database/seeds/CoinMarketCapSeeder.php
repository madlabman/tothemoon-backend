<?php

use App\CryptoCurrency;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Seeder;

class CoinMarketCapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'https://api.coinmarketcap.com/v2/listings/');
            $status = $res->getStatusCode();
            if ($status == 200) {
                $ticker = $res->getBody();
                $ticker = json_decode($ticker);
                foreach ($ticker->data as $coin) {
                    CryptoCurrency::updateOrCreate([
                        'name'      => $coin->name,
                        'symbol'    => $coin->symbol,
                        'market_id' => $coin->id,
                    ]);
                }
            }
        } catch (GuzzleException $ex) {
            echo 'Request error.';
        }
    }
}
