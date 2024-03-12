<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use MoneroIntegrations\MoneroPhp\walletRPC;
use App\Models\Address;
use App\Models\MarketFunction;
use App\Models\Server;
use App\Models\Wallet;

class DepositWithdrawAlert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    
        if (MarketFunction::where('name', 'wallet')->first()->enable == 1) {
            # code...
       
        $server = Server::where('type', 'wallet')->first();

        // Establish connection to Monero wallet
        $walletRPC = new walletRPC($server->ip, $server->port, false, $server->username, $server->password);
        $walletRPC->open_wallet($server->extra_user, $server->extra_pass);

        // Fetch all addresses
        $addresses = Address::all();

        // Exit early if there are no addresses
        if (empty($addresses)) {
            return $next($request);
        }

        foreach ($addresses as $address) {
            if ($address->expired === 0) {
                $addIndex = $walletRPC->get_address_index($address->address);

                if (!empty($addIndex)) {
                    
                  // Now that $address is set, use it in get_transfers
                    $transactionDetails = $walletRPC->get_transfers('in', $addIndex['index']['major'], [ $addIndex['index']['minor']]);

                    if (!empty($transactionDetails)) {
                        Wallet::processTransactionDetails($address, $transactionDetails);
                    }
                }
            }
        }


            $transfers = $walletRPC->get_transfers('out', true);

             if (!empty($transfers)) {
                Wallet::ProccessOutGoinTransactions($transfers);
             }
        
            }
        return $next($request);
    }
}
