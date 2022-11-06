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

    $TxMutationParser = new TxMutationParser($txresult->Account, $txresult);
    $parsedTransaction = $TxMutationParser->result();

    if(!$reference_account)
      $reference_account = $txresult->Account;
    
    $parsedRefTransaction = null;
    if($txresult->Account != $reference_account) {
      $TxMutationRefParser = new TxMutationParser($reference_account, $txresult);
      $parsedRefTransaction = $TxMutationRefParser->result();
    }

    $participating_accounts = \array_keys($parsedTransaction['allBalanceChanges']);

    $formatted_currencies = ['XRP' => 'XRP'];
    //format all currencies
    foreach($parsedTransaction['allBalanceChanges'] as $v) {
      foreach($v['balances'] as $b) {
        if($b['currency'] !== 'XRP') {
          $formatted_currencies[$b['currency']] = xrp_currency_to_symbol($b['currency'],$b['currency']);
        }
      }
    }

    return response()->json([
      'reference_account' => $reference_account,
      'participating_accounts' => $participating_accounts,
      'parsed' => $parsedTransaction,
      'parsed_ref' => $parsedRefTransaction,
      'formatted_currencies' => $formatted_currencies,
      'raw' => $txresult
    ]);
  }
}
