<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
  <title>{{config('app.name')}} — unlock power of XRPL</title>
  <link rel="canonical" href="{{config('app.url')}}" />

  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="{{config('app.name')}} - unlock power of XRPL" />
  <meta property="og:description" content="This is playground for XRPL, play!" />
  <meta property="og:url" content="{{config('app.url')}}" />
  <meta property="og:site_name" content="{{config('app.name')}}" />
  <meta property="article:modified_time" content="2022-11-04T10:18:20+00:00" />
  <meta property="og:image" content="/res/images/xrplwinplaygroundbg.jpg" />
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="628" />
  <meta property="og:image:type" content="image/jpeg" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:label1" content="Est. reading time" />
  <meta name="twitter:data1" content="5 minutes" />

  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.0/css/all.min.css?ver=1.0.0' type='text/css' media='all' />
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.0/css/v4-shims.min.css?ver=1.0.0' type='text/css' media='all' />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="/res/css.css" />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500&display=swap" rel="stylesheet"> 
</head>
<body class="bg-dark text-light">
  <nav class="navbar sticky-top navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand text-uppercase" href="/">
        <img src="/res/images/xrplwin_logo_80.webp" alt="W" width="40" height="24" class="d-inline-block align-text-top">
        Playground
      </a>
    </div>
  </nav>
  @yield('content')
  <script src="/res/js.js"></script>
</body>
</html>