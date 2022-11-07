function xrpaddress_to_short(a) {
  return a.substring(0,4)+'&mldr;'+a.slice(-4);
}