<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\ResponseTrait;

class LogUserAuto extends Command
{
    use ResponseTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:log-user-auto';

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
        $response = Http::get('https://randomuser.me/api/');

        if ($response->successful()) {
            $data = $response->json()["results"][0];
            User::create([
               "name"=>$data['name']['title'] .' ' . $data['name']['first'].' ' . $data['name']['last'],
               "email"=>$data['email'],
               "phone"=>$data['phone'],
               'password'=>bcrypt($data['login']['password']),
            ]);

        } else {
            // Handle the case where the request was not successful
            $statusCode = $response->status();
        }
    }
}
