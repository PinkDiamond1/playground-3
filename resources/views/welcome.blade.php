@extends('layouts.app')

@section('content')
<div class="container">

  <div class="card text-white bg-lighter mb-5">
    <div class="card-body p-4">
      <h1 class="card-title">Welcome to XRPLWin Playground</h1>
      <h6 class="card-subtitle mb-2 text-muted">Explore and test packages and API</h6>
      <p class="card-text">
      Here you can find packages we are working on, this page uses those packages to process and display XRPL data.
      <br>
      All packages are open source and available on <a href="https://github.com/XRPLWin" target="_blank"><i class="fab fa-github"></i> GitHub</a>
      </p>
      
      <a href="#" class="btn btn-primary">Get started</a>
      <a href="https://github.com/XRPLWin" target="_blank" class="btn btn-outline-light"><i class="fab fa-github"></i> GitHub</a>
    </div>
  </div>
  <div class="card text-white bg-darker">
    <div class="card-body">
      <h5 class="card-title">Welcome to XRPLWin Playground</h5>
      <h6 class="card-subtitle mb-2 text-muted">Explore and test packages and API</h6>
      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
      <a href="#" class="card-link">Card link</a>
      <a href="#" class="card-link">Another link</a>
    </div>
  </div>

</div>
@endsection