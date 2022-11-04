@extends('layouts.app')

@section('content')
<div class="container pt-4">

  <div class="card text-white bg-lighter mb-5">
    <div class="card-body p-4">
      <h3 class="card-title mb-4">Welcome to XRPLWin Playground</h3>
      <p class="card-text">
      Here you can find packages we are working on, this page uses those packages to process and display XRPL data.
      <br>
      All packages are open source and available on <i class="fab fa-github"></i> <a href="https://github.com/XRPLWin" target="_blank">GitHub</a> page.
      </p>
      
      <a href="#" class="btn btn-primary">Get started</a>
      <a href="https://github.com/XRPLWin" target="_blank" class="btn btn-outline-light"><i class="fab fa-github"></i> GitHub</a>
    </div>
  </div>
  <div class="card text-white bg-darker">
    <div class="card-body p-4">
      <div class="row">
        <div class="col-12 col-md-6">
          <h5 class="card-title">XRPL Transaction Mutation Parser</h5>
          <p class="card-text">
            Parse XRPL transaction to context aware object for visual representation.
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

           <a href="#" class="btn btn-warning">Try it</a>
           <a href="https://github.com/XRPLWin/XRPL-TxMutationParser" target="_blank" class="btn btn-outline-light"><i class="fab fa-github"></i> GitHub</a>
        </div>
        <div class="col-12 col-md-6">
          <img src="https://hooks.xrpl.org/images/hooks-builder.png" class="img-fluid" alt="Screenshot" />
        </div>
      </div>
    </div>
  </div>

</div>
@endsection