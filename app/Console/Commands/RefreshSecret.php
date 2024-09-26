<?php

namespace App\Console\Commands;

use App\Exceptions\UserException;
use App\Models\UserIpToken;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;

class RefreshSecret extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh-secret';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Laravel\Prompts\info("Avvio schedule ".Carbon::now()->format('d/m/Y H:i:s'));
        \Laravel\Prompts\info("Schedule elimino sia token sia le righe su access token più vecchie di due ore....");

        $accessToken = Passport::token()->whereBetween('created_at',[Carbon::now()->subHours(10),Carbon::now()->subHours(2)] )->get();

        if($accessToken->count() > 0){
            $accessToken->map(function ($item){
                $item->revoked = 1;
                $item->save();
            });
        }
        \Laravel\Prompts\info("...Schedule elimino terminato");
        \Laravel\Prompts\info("Schedule refresh secret avviato....");
        $passport = Client::all();
        $passport->each(function (Client $clients){
            try {
                \Laravel\Prompts\info("Each clients");
                $repository = app(ClientRepository::class);
                $repository->regenerateSecret($clients);
            }catch (\Exception $e){
                throw new \Exception($e->getMessage(),$e->getCode());
            }
        });
        \Laravel\Prompts\info("...Schedule refresh secret terminato");

        \Laravel\Prompts\info("Schedule terminato ".Carbon::now()->format('d/m/Y H:i:s'));
    }
}
