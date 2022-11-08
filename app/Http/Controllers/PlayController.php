<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayController extends Controller
{
  public function txmutationparser(Request $request)
  {
    $hash = $request->input('hash');
    if(!$hash)
      $hash = 'A357FD7C8F0BBE7120E62FD603ACBE98819BC623D5D12BD81AC68564393A7792';
    $ref1 = $request->input('ref1');
    return view('txmutationparser.index', compact('hash','ref1'));
  }

  public function orderbookreader(Request $request)
  {
    return view('orderbookreader.index');
  }

  public function nftviewer(Request $request)
  {
    $address = $request->input('address');
    return view('nftviewer.index', compact('address'));
  }
}
