function xrpaddress_to_short(a) {
  return a.substring(0,4)+'....'+a.slice(-4);
}