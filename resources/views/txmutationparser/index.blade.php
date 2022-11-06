@extends('layouts.app')


@section('content')
<div class="container pt-4">

  <div class="card bg-lighter mb-3">
    <div class="card-body">
      <h5 class="card-title">XRPL Transaction Mutation Parser</h5>
      
        <form method="GET">
          <div class="input-group mb-3">
            <input type="text" name="hash" class="form-control" placeholder="Transaction hash" aria-label="Transaction hash" aria-describedby="button-addon2" value="{{$hash}}">
            <button class="btn btn-warning" type="submit" id="button-addon2">Parse Tx</button>
          </div>
          <div class="mb-3">
            <label class="form-label">Participants</label>
            <div id="form-participating_accounts">
              <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>

          
        </form>

    </div>
  </div>

  <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="visualized-tab" data-bs-toggle="tab" data-bs-target="#visualized-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Visualized</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="parsed-tab" data-bs-toggle="tab" data-bs-target="#parsed-tab-pane" type="button" role="tab" aria-controls="parsed-tab-pane" aria-selected="false"><i class="fa-solid fa-code"></i> Parsed</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link d-none" id="parsedref-tab" data-bs-toggle="tab" data-bs-target="#parsedref-tab-pane" type="button" role="tab" aria-controls="parsedref-tab-pane" aria-selected="false"><i class="fa-solid fa-code"></i> Parsed perspective</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="tx-tab" data-bs-toggle="tab" data-bs-target="#tx-tab-pane" type="button" role="tab" aria-controls="tx-tab-pane" aria-selected="false"><i class="fa-solid fa-code"></i> Raw Transaction</button>
    </li>
  </ul>
  <div class="tab-content mb-5">
    <div class="tab-pane fade show active" id="visualized-tab-pane" role="tabpanel" aria-labelledby="visualized-tab" tabindex="0">
     

      <div class="d-flex justify-content-center" id="visualized-tab-pane-loader">
         <div class="spinner-border text-light" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <div id="visualized-tab-pane-content" class="d-none text-center">
        <h2 id="v-txtype"></h2>

        <div class="row">
          <div class="col-6" id="v">
            Initiator:
            <h5><div id="v-self" class="badge text-bg-primary"></div></h5>
            <h4><div id="v-type" class="badge rounded-pill text-bg-success">...</div></h4>
            
            <div id="v-eventflow" class="mb-4">-</div>

            Final balance changes:
            <h4 id="v-selfbalancechanges" class="mb-4">-</h4>
          </div>
          <div class="col-6" id="vref">
            Perspective:
            <h5><div id="vref-self" class="badge text-bg-primary"></div></h5>
            <h4><div id="vref-type" class="badge rounded-pill text-bg-success">...</div></h4>
            
            <div id="vref-eventflow" class="mb-4">-</div>

            Final balance changes:
            <h4 id="vref-selfbalancechanges" class="mb-4">-</h4>
          </div>
        </div>

        <!--<div><i class="fa-solid fa-arrow-down"></i></div>-->

        {{--eventFlow--}}
        <div id="v-eventflow"></div>

      </div>

    </div>
    <div class="tab-pane fade" id="parsed-tab-pane" role="tabpanel" aria-labelledby="parsed-tab" tabindex="0">
      <pre id="parsed-tab-pane-json-display" class="rounded p-4"></pre>
    </div>
    <div class="tab-pane fade" id="parsedref-tab-pane" role="tabpanel" aria-labelledby="parsedref-tab" tabindex="0">
      <pre id="parsedref-tab-pane-json-display" class="rounded p-4"></pre>
    </div>
    <div class="tab-pane fade" id="tx-tab-pane" role="tabpanel" aria-labelledby="tx-tab" tabindex="0">
      <pre id="tx-tab-pane-json-display" class="rounded p-4"></pre>
    </div>
  </div>

</div>
@endsection
@push('javascript')
@if($hash)
<script>
var formatted_currencies = {};
function formatPrice(data)
{
  var positive = !(data.value.slice(0, 1) === '-');

    r = '<span class="text-'+(positive?'lime':'red')+'">'+(positive?'+':'')+''+data.value+' '+formatCurrency(data.currency)+'<span> '+(data.counterparty? xrpaddress_to_short(data.counterparty):'');
    return r;
}

function formatCurrency(currency) {
  if(!formatted_currencies[currency])
    return '<span title="'+currency+'" class="fw-bold">'+currency+'</span>';
  return '<span title="'+currency+'" class="fw-bold">'+formatted_currencies[currency]+'</span>';
}
//const xw_xrpl_wss_server = "xrplcluster.com";
//const hash = "{{$hash}}";
//http://playground.test/play/xrpl-transaction-mutation-parser?ref=rcoreNywaoz2ZCQ8Lg2EbSLnGuRBmun6D
function visualize(suffix,data,p)
{

  $("#v"+suffix+"-self").text(p.self.account);
  $("#v"+suffix+"-type").text(p.type);

  //Balance changes:
  var balchanges = '';
  $.each(p.self.balanceChanges,function(k,v){
    balchanges += '<div>'+formatPrice(v)+'</div>';
  });
  $("#v"+suffix+"-selfbalancechanges").html(balchanges);

  //Event flow:

  var eventflow = '';
  if(p.eventFlow.start) {
    eventflow += '<div><div class="badge text-bg-info">START</div></div>';
    eventflow += '<div><div class="badge text-bg-secondary">'+p.eventFlow.start.account+'</div></div>';
    eventflow += '<div>'+formatPrice(p.eventFlow.start.mutation)+'</div>';
  }
  if(p.eventFlow.intermediate) {
    eventflow += '<div><div class="badge text-bg-info">INTERMEDIATE</div></div>';
    eventflow += '<div><div class="badge text-bg-secondary">'+p.eventFlow.intermediate.account+'</div></div>';
    if(p.eventFlow.intermediate.mutations) {
      if(p.eventFlow.intermediate.mutations.in) {
        eventflow += '<div>IN '+formatPrice(p.eventFlow.intermediate.mutations.in)+'</div>';
      }
      if(p.eventFlow.intermediate.mutations.out) {
        eventflow += '<div>OUT '+formatPrice(p.eventFlow.intermediate.mutations.out)+'</div>';
      }
    } else {
      alert('no int. mutations');
    }
    //eventflow += p.eventFlow.end.mutation.value+" "+p.eventFlow.end.mutation.currency;
  }
  if(p.eventFlow.end) {
    eventflow += '<div><div class="badge text-bg-info">END</div></div>';
    eventflow += '<div><div class="badge text-bg-secondary">'+p.eventFlow.end.account+'</div></div>';
    eventflow += '<div>'+formatPrice(p.eventFlow.end.mutation)+'</div>';
  }
  $("#v"+suffix+"-eventflow").html(eventflow);
  return;
  
  
  

  var balchanges = 'Balance changes:<br>';

  $.each(p.self.balanceChanges,function(k,v){
    balchanges += '<div>'+formatPrice(v)+'</div>';
  });
  /*
  if(p.eventList.primary) {
    balchanges += formatPrice(p.eventList.primary);
  }
  if(p.eventList.secondary) {
    balchanges += formatPrice(p.eventList.secondary);
  }*/
  $("#v-selfbalancechanges").html(balchanges);



  
  //eventflow <div><i class="fa-solid fa-arrow-down"></i>
  var eventflow = '';
  if(p.eventFlow.start) {
    eventflow += '<div><div class="badge text-bg-info">START</div></div>';
    eventflow += '<div><div class="badge text-bg-secondary">'+p.eventFlow.start.account+'</div></div>';
    eventflow += '<div>'+formatPrice(p.eventFlow.start.mutation)+'</div>';
  }
  if(p.eventFlow.intermediate) {
    eventflow += '<div><div class="badge text-bg-info">INTERMEDIATE</div></div>';
    eventflow += '<div><div class="badge text-bg-secondary">'+p.eventFlow.intermediate.account+'</div></div>';
    if(p.eventFlow.intermediate.mutations) {
      if(p.eventFlow.intermediate.mutations.in) {
        eventflow += '<div>'+formatPrice(p.eventFlow.intermediate.mutations.in)+'</div>';
      }
      if(p.eventFlow.intermediate.mutations.out) {
        eventflow += '<div>'+formatPrice(p.eventFlow.intermediate.mutations.out)+'</div>';
      }
    } else {
      alert('no int. mutations');
    }
    //eventflow += p.eventFlow.end.mutation.value+" "+p.eventFlow.end.mutation.currency;
  }
  if(p.eventFlow.end) {
    eventflow += '<div><div class="badge text-bg-info">END</div></div>';
    eventflow += '<div><div class="badge text-bg-secondary">'+p.eventFlow.end.account+'</div></div>';
    eventflow += '<div>'+formatPrice(p.eventFlow.end.mutation)+'</div>';
  }

  $("#v-eventflow").html(eventflow);
}
function process_response(data)
{
  new JsonEditor('#parsed-tab-pane-json-display',data.parsed,{editable:false});
  if(data.parsed_ref) {
    new JsonEditor('#parsedref-tab-pane-json-display',data.parsed_ref,{editable:false});
    $("#parsedref-tab").removeClass('d-none');
  }
    
  new JsonEditor('#tx-tab-pane-json-display',data.raw,{editable:false});
}
function process_participating_accounts(data)
{
  var r = '';
  $.each(data.participating_accounts, function (k,v){
    r += '<a class="badge rounded-pill text-bg-light text-decoration-none" href="{{route('play.txmutationparser.index',['hash' => $hash])}}&ref='+v+'" title="'+v+'">'+xrpaddress_to_short(v)+'</a> ';
  })
  $("#form-participating_accounts").html(r);
}
$(function(){
  $.ajax({
      type:'GET',
      dataType: "json",
      url: "{{route('api.tx',['hash' => $hash, 'reference_account' => $ref])}}",
      data: {},
      success: function(d){
        formatted_currencies = d.formatted_currencies;
        process_participating_accounts(d);
        $("#v-txtype").text(d.raw.TransactionType);
        $("#visualized-tab-pane-loader").remove();
        $("#visualized-tab-pane-content").removeClass("d-none");
          console.log(formatted_currencies);
        //visualize left side
        visualize('',d,d.parsed);
        
        
        if(d.parsed_ref) {
          //visualize right side
          visualize('ref',d,d.parsed_ref);
        }
        else {
          $("#vref").addClass('visually-hidden');
        }

        process_response(d);
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