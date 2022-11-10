@extends('layouts.app')

@section('content')
<div class="container pt-4">


<div class="p-5 mb-4">
      <h3 class="card-title mb-4">Welcome to XRPLWin Playground</h3>
      <p class="card-text">
      Here you can find packages and other tools we are working on, this page uses those packages to process and display XRPL data.
      <br>
      All packages are open source and available on our <i class="fab fa-github"></i> <a href="https://github.com/XRPLWin" target="_blank">GitHub</a> page.
      </p>
      
      <!--<a href="#" class="btn btn-primary">Get started</a>
      <a href="https://github.com/XRPLWin" target="_blank" class="btn btn-outline-light"><i class="fab fa-github"></i> GitHub</a>-->
    </div>
{{--


  <div class="card text-white bg-darker mb-4">
    <div class="card-body p-4">
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <h5 class="card-title">NFT viewer</h5>
          <p>
            View NFT in all its glory.
          </p>
          
          <div class="mt-4">
            <a href="{{route('play.nftviewer.index')}}" class="btn btn-warning">Try it</a>
          </div>
           
        </div>
        <div class="col-12 col-md-6">
          <a href="{{route('play.nftviewer.index')}}">
            <img src="https://hooks.xrpl.org/images/hooks-builder.png" class="img-fluid rounded-3" alt="Screenshot" />
          </a>
        </div>
      </div>
    </div>
  </div>
--}}
  <div class="card text-white bg-darker mb-4">
    <div class="card-body p-4">
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <h5 class="card-title">XRPL Transaction Mutation Parser</h5>
          <p>
            Parse XRPL transaction to <i>context aware</i> object for visual representation.
          </p>
          <p>
            It takes a XRPL transaction (outcome, meta) and an XRPL account.
            The XRPL account is the context from which the XPRL transaction is to be interpreted.
          </p>
          <p>
            The account can be the sender, recipient, or an intermediate account.
            An intermediate account applies if e.g. there's a trade happening, touching your own offer asynchronously.
            You put up an offer and at some point down the road it gets (possibly partially) consumed.
            Alternatively, you can be an Intermediate account if you are a regular key signer or if something is rippling through your account.
          </p>
          <div class="mt-4">
            <a href="{{route('play.txmutationparser.index')}}?hash=A357FD7C8F0BBE7120E62FD603ACBE98819BC623D5D12BD81AC68564393A7792&ref=rhub8VRN55s94qWKDv6jmDy1pUykJzF3wq" class="btn btn-warning">Try it</a>
            <a href="https://github.com/XRPLWin/XRPL-TxMutationParser" title="Source for PHP by XRPLWin" target="_blank" class="btn btn-outline-light"><i class="fab fa-github"></i> GitHub <i class="fa-brands fa-php"></i></a>
            <a href="https://github.com/XRPL-Labs/TxMutationParser" title="Source for NodeJS by XRPL Labs" target="_blank" class="btn btn-outline-light"><i class="fab fa-github"></i> GitHub <i class="fa-brands fa-node-js"></i></a>
          </div>
           
        </div>
        <div class="col-12 col-md-6">
          <a href="{{route('play.txmutationparser.index')}}?hash=A357FD7C8F0BBE7120E62FD603ACBE98819BC623D5D12BD81AC68564393A7792&ref=rhub8VRN55s94qWKDv6jmDy1pUykJzF3wq">
            <img src="/res/images/txmutationparser-ss.jpg" class="img-fluid rounded-3" alt="Screenshot" />
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="card text-white bg-darker mb-4 shadow">
    <div class="card-body p-4">
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <h5 class="card-title">XRPL Orderbook Reader</h5>
          <p>
            This repository takes XRPL Orderbook (<kbd>book_offers</kbd>) datasets and requested volume to exchange and calculates the effective exchange rates based on the requested and available liquidity.
          </p>
          <p>
            Optionally certain checks can be specified (eg. <kbd>book_offers</kbd> on the other side of the book) to warn for limited (percentage) liquidity on the requested side, and possibly other side of the order book.
          </p>
          
          <div class="mt-4">
            {{--<a href="{{route('play.orderbookreader.index')}}" class="btn btn-warning">Try it</a>--}}
            <a href="https://github.com/XRPLWin/XRPL-Orderbook-Reader" title="Source for PHP by XRPLWin" target="_blank" class="btn btn-outline-light"><i class="fab fa-github"></i> GitHub <i class="fa-brands fa-php"></i></a>
            <a href="https://github.com/XRPL-Labs/XRPL-Orderbook-Reader" title="Source for NodeJS by XRPL Labs" target="_blank" class="btn btn-outline-light"><i class="fab fa-github"></i> GitHub <i class="fa-brands fa-node-js"></i></a>
          </div>
           
        </div>
        <div class="col-12 col-md-6">
          <a href="#{{route('play.orderbookreader.index')}}">
            <img src="/res/images/orderbook-ss.jpg" class="img-fluid rounded-3" alt="Screenshot" />
          </a>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection