function xrpaddress_to_short(a) {
  return a.substring(0,4)+'&mldr;'+a.slice(-4);
}
function Hex2Bin(n){if(!checkHex(n))return 0;return parseInt(n,16).toString(2)}
function ipfsuritourl(uri) {
  var a = uri.slice(0,7);
  if(a === 'ipfs://') {
    return 'https://ipfs.io/ipfs/'+uri.substring(7);
  }
  return uri;
  alert(a);
}