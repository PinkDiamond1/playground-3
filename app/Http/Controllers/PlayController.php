<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayController extends Controller
{
  public function txmutationparser(Request $request)
  {
    $hash = $request->input('hash');
    if(!$hash)
      $hash = 'E0382D408F1BD7835E86336B43EBD43C7543779BDECD406B0BC00BA7CB86CE13';
    $ref = $request->input('ref');

    return view('txmutationparser.index', compact('hash','ref'));
  }

  public function orderbookreader(Request $request)
  {
    return view('orderbookreader.index');
  }
}
