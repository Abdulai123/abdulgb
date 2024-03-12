<?php

namespace App\Http\Middleware;

use App\Models\MarketFunction;
use App\Models\Server;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\TryCatch;

class BtcXmrUSD
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        # code...
if (MarketFunction::where('name', 'api')->first()->enable == 1) {
    # code...

        $api_servers = Server::where('type', 'api')->get();

        foreach ($api_servers as $api) {
            // Check if the last update was more than 15 minutes ago
            $lastUpdate = Carbon::parse($api->updated_at);
            if ($lastUpdate->diffInMinutes(now()) >= 15) {
                if ($api->username == 'XMR' || $api->username == 'xmr') {
                   try {
                    $responseXMR = Http::get($api->ip);
                    // Check if the request was successful
                    if ($responseXMR->successful()) {
                        $api->port = $responseXMR['USD'];
                        $api->save();
                    }
                   } catch (\Throwable $e) {
                    //throw $th;
                   }

                } elseif ($api->username == 'BTC' || $api->username == 'btc') {
                    try {
                        $responseBTC = Http::get($api->ip);
                        // Check if the request was successful
                        if ($responseBTC->successful()) {
                            $api->port = $responseBTC['USD'];
                            $api->save();
                        }
                    } catch (\Throwable $th) {
                        //throw $th;
                    }

                }
            }
        }

        session(['xmr' => $api->where('username', 'XMR')->orwhere('username', 'xmr')->first()->port]);
        session(['btc' => $api->where('username', 'BTC')->orwhere('username', 'btc')->first()->port]);
    }
        return $next($request);
    }
}
