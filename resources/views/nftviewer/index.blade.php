@extends('layouts.app')


@section('content')
<div class="container pt-4">

  <div class="card bg-lighter mb-3">
    <div class="card-body">
      <h5 class="card-title">NFT viewer</h5>
      
        <form method="GET">
          <div class="input-group mb-3">
            <input type="text" name="address" class="form-control" placeholder="Account address" aria-label="Account address" aria-describedby="button-addon2" value="{{$address}}">
            <button class="btn btn-warning" type="submit" id="button-addon2">View NFTs</button>
          </div>
        </form>

    </div>
  </div>

  <div class="d-flex justify-content-center" id="loader">
      <div class="spinner-border text-light" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <div class="row" id="nftlist"></div>

  

</div>
@endsection
@push('head')
@endpush
@push('javascript')
@if($address)
<script>
var tx;
/*function process_response(data)
{
  new JsonEditor('#parsed1-tab-pane-json-display',data.parsed1,{editable:false});
  if(data.parsed2) {
    new JsonEditor('#parsed2-tab-pane-json-display',data.parsed2,{editable:false});
    $("#parsed2-tab").removeClass('d-none');
  }
  new JsonEditor('#tx-tab-pane-json-display',data.raw,{editable:false});
}*/

function shownftlist(r)
{
  $.each(r,function(k,v){
    var h = '<div class="col-12 col-lg-3 col-md-6"><div class="border rounded p-3 bg-dark mb-2">';

    if(v.ipfsdata.schema == 'ipfs://QmNpi8rcXEkohca8iXu7zysKKSJYqCvBJn3xJwga8jXqWU') {
      h += '<h5 class="text-center">'+v.ipfsdata.name+'</h5>';
      if(v.ipfsdata.animation) {
        h += '<div class="text-center"><video class="shadow" controls="true" autoplay="false" name="media" style="margin-bottom: 10px;" width="100%"><source src="'+ipfsuritourl(v.ipfsdata.animation)+'" type="video/mp4"></video></div>';
      } else if(v.ipfsdata.image) {
        h += '<div class="text-center"><img class="img-fluid shadow bg-black rounded-3" src="'+ipfsuritourl(v.ipfsdata.image)+'" /></div>';
      }
      
      h += '<div class="small mt-2">'+v.ipfsdata.description+'</div>';
     }
     h += '</div></div>';
    $("#nftlist").append(h);
  })
}

$(function(){
  $.ajax({
      type:'GET',
      dataType: "json",
      url: "{!!route('api.account_nfts',['address' => $address])!!}",
      data: {},
      success: function(d){
        tx = d;
        $("#loader").remove();
        shownftlist(d);
      },
      error: function(a,d,c){
        alert('Failed to fetch tx info, try again later')
      },
      complete:function() {}
    });
})

</script>
@endif
@endpush