<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use XRPLWin\XRPLTxMutatationParser\TxMutationParser;

class XrplController extends Controller
{
  public function tx(string $hash, Request $request)
  {
    $request->validate([
      'ref1' => 'nullable|string',
      'ref2' => 'nullable|string',
    ]);
    $ref1 = $request->input('ref1');
    $ref2 = $request->input('ref2');
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

    if(!$ref1)
      $ref1 = $txresult->Account;

    $TxMutationParser = new TxMutationParser($txresult->Account, $txresult);
    $parsedTransaction = $TxMutationParser->result();
    $participating_accounts = \array_keys($parsedTransaction['allBalanceChanges']);



    $TxMutationRef1Parser = new TxMutationParser($ref1, $txresult);
    $parsedRef1Transaction = $TxMutationRef1Parser->result();

    $parsedRef2Transaction = null;
    if($ref2) {
      $TxMutationRef2Parser = new TxMutationParser($ref2, $txresult);
      $parsedRef2Transaction = $TxMutationRef2Parser->result();
    }

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
      //'ref1' => $ref1,
      //'ref2' => $ref2,
      'participating_accounts' => $participating_accounts,
      'parsed1' => $parsedRef1Transaction,
      'parsed2' => $parsedRef2Transaction,
      'formatted_currencies' => $formatted_currencies,
      'raw' => $txresult
    ]);
  }
}
