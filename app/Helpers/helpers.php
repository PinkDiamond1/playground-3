<?php

if (!function_exists('xrp_currency_to_symbol')) {
    /**
    * Decode HEX XRPL currency to symbol.
    * If already symbol returns that symbol (checked by length).
    * Examples: USD,EUR,534F4C4F00000000000000000000000000000000
    * @return string
    */
    function xrp_currency_to_symbol($currencycode, $malformedUtf8ReturnString = '?') : string
    {
      if( \strlen($currencycode) == 40 )
      {
        $r = \trim(\hex2bin($currencycode));
        $r = preg_replace('/[\x00-\x1F\x7F]/', '', $r); //remove first 32 ascii characters and \x7F https://en.wikipedia.org/wiki/Control_character
        if(preg_match('//u', $r)) //This will will return 0 (with no additional information) if an invalid string is given.
          return $r;
        return $malformedUtf8ReturnString; //malformed UTF-8 string
      }
      return $currencycode;
    }
  }