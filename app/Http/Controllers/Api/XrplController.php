<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use XRPLWin\XRPLTxMutatationParser\TxMutationParser;

class XrplController extends Controller
{
  public function tx(string $hash, ?string $reference_account = null)
  {
    $client = new \XRPLWin\XRPL\Client([
      # Following values are defined by default, uncomment to override
      //'endpoint_reporting_uri' => 'http://s1.ripple.com:51234',
      //'endpoint_fullhistory_uri' => 'https://xrplcluster.com'
    ]);
    $tx = $client->api('tx')->params([
        'transaction' => $hash,
        'binary' => false
    ]);

    try {
      $tx->send();
    } catch (\XRPLWin\XRPL\Exceptions\XWException $e) {
      // Handle errors
      abort(422);
      throw $e;
    }
    $txresult = $tx->finalResult();

    if(!$reference_account)
      $reference_account = $txresult->Account;
    
    $TxMutationParser = new TxMutationParser($reference_account, $txresult);
    $parsedTransaction = $TxMutationParser->result();

    $participating_accounts = \array_keys($parsedTransaction['allBalanceChanges']);

    return response()->json([
      'reference_account' => $reference_account,
      'participating_accounts' => $participating_accounts,
      'parsed' => $parsedTransaction,
      'raw' => $txresult
    ]);
  }
}
