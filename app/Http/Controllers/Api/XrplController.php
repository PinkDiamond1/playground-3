<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use XRPLWin\XRPLTxMutatationParser\TxMutationParser;

class XrplController extends Controller
{

  public function account_nfts(string $address, Request $request)
  {
    $client = new \XRPLWin\XRPL\Client([
      # Following values are defined by default, uncomment to override
      //'endpoint_reporting_uri' => 'http://s1.ripple.com:51234',
      //'endpoint_fullhistory_uri' => 'https://xrplcluster.com'
    ]);
    $tx = $client->api('account_nfts')->params([
        'account' => $address,
        'ledger_index' => 'validated',
        'limit' => 6, //showing first 20 nfts
    ]);

    try {
      $tx->send();
    } catch (\XRPLWin\XRPL\Exceptions\XWException $e) {
      // Handle errors
      abort(422);
      throw $e;
    }

    $nftsData = $tx->finalResult();

    $nfts = [];
    foreach($nftsData as $nft) {
      $ipfs = \hex2bin($nft->URI);
      $nfts[] = [
        'ipfs' => $ipfs,
        'ipfsdata' => $this->ipfs_uri_to_response($ipfs),
        'raw' => $nft
      ];
    }
    return response()->json($nfts);
  }

  private function ipfs_uri_to_response(string $ipfs_uri): ?\stdClass
  {
    $client = new \GuzzleHttp\Client;
    $url = null;

    if(str_starts_with($ipfs_uri,'ipfs://')) {
      $ex = \explode('://',$ipfs_uri);
      $url = 'https://ipfs.io/ipfs/'.$ex[1];
    } else {
      $url = $ipfs_uri;
    }

    

    try{
      $res = $client->request('GET', $url, [
        'http_errors' => false,
        'connect_timeout' => 10,
        //'body' => \json_encode($p),
        'headers' => [ 'Content-Type' => 'application/json' ]
      ]);
    } catch (\Throwable $e) {
      dd(422);
      exit;
    }
    return \json_decode((string)$res->getBody(),false);
  }


  public function tx(string $hash, Request $request)
  {
    $request->validate([
      'ref' => 'nullable|string',
    ]);
    $ref = $request->input('ref');

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

    if(!$ref)
      $ref = $txresult->Account;

    $TxMutationParser = new TxMutationParser($txresult->Account, $txresult);
    $parsedTransaction = $TxMutationParser->result();
    $participating_accounts = \array_keys($parsedTransaction['allBalanceChanges']);

    $parses = [];
    foreach($participating_accounts as $pacc) {
      $TxMutationParser = new TxMutationParser($pacc, $txresult);
      $parses[$pacc] = $TxMutationParser->result();
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
      'parsed' => $parses[$ref],
      'parsedall' => $parses,
      'formatted_currencies' => $formatted_currencies,
      'raw' => $txresult
    ]);
  }
}
