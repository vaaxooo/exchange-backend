<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Coin;

class RefreshRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rates:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the rate of cryptocurrencies through Binance';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Updating rates...');
        $coins = Coin::get();
        foreach ($coins as $coin) {
            $rate = $this->CURL('https://api.binance.com/api/v3/ticker/price?symbol=' . strtoupper($coin->symbol) . 'USDT');
            $rate = json_decode($rate);
            if (isset($rate->price)) {
                Coin::where('id', $coin->id)->update(['exchange_rate' => $rate->price]);
                $this->info($coin->name . ' rate updated to ' . $coin->exchange_rate);
            } else {
                $this->error('Error updating ' . $coin->name . ' rate');
            }
        }
        $this->info('Rates updated successfully!');
        return Command::SUCCESS;
    }

    /**
     * It takes a URL and returns the response.
     * 
     * @param url The URL of the API endpoint you want to call.
     * 
     * @return The response from the API.
     */
    private function CURL($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
