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
     
  <div id="test123">1.00000012</div>
      <div class="d-flex justify-content-center" id="visualized-tab-pane-loader">
         <div class="spinner-border text-light" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <div id="visualized-tab-pane-content" class="d-none text-center">
        <h2 id="v-txtype"></h2>

        <table class="table table-borderless table-sm text-light">
          <tr>
            <td width="50%">
              <div class="box mb-3">
                <div class="box-title text-start p-1">
                  <span class="text-uppercase text-muted "><i class="fa-solid fa-angle-left small"></i>Initiator<i class="fa-solid fa-angle-right text-muted small"></i></span>
                  <span id="v-self"></span>
                  <div id="v-type" class="badge rounded-pill text-bg-success float-end">...</div>
                </div>

                <div class="p-1 text-start">
                  <table class="table table-borderless table-sm">
                    <tr>
                      <td width="50%" valign="bottom">
                        <div class="text-uppercase text-muted text-end small">Balance changes <i class="fa-solid fa-angle-right text-muted small"></i> Value <i class="fa-solid fa-angle-down small"></i></div>
                      </td>
                      <td width="50%" valign="bottom">
                        <div class="text-uppercase text-muted text-start small">Currency <i class="fa-solid fa-angle-down small"></i></div>
                      </td>
                    </tr>
                  </table>
                  <div id="v-selfbalancechanges"></div>
                  <div class="text-end box-footer">
                    <div class="small text-uppercase"><i class="fa-solid fa-angle-left text-muted small"></i>Status : pending<i class="fa-solid fa-angle-right text-muted small"></i> </div>
                  </div>
                </div>
              </div>{{--/end .box--}}
            </td>
            <td width="50%">
              

              <div class="box mb-3">
                <div class="box-title text-start p-1">
                  <span class="text-uppercase text-muted "><i class="fa-solid fa-angle-left small"></i>Perspective<i class="fa-solid fa-angle-right text-muted small"></i></span>
                  <span id="vref-self"></span>
                  <div id="vref-type" class="badge rounded-pill text-bg-success float-end">...</div>
                </div>

                <div class="p-1 text-start">
                  <table class="table table-borderless table-sm">
                    <tr>
                      <td width="50%" valign="bottom">
                        <div class="text-uppercase text-muted text-end small">Balance changes <i class="fa-solid fa-angle-right text-muted small"></i> Value <i class="fa-solid fa-angle-down small"></i></div>
                      </td>
                      <td width="50%" valign="bottom">
                        <div class="text-uppercase text-muted text-start small">Currency <i class="fa-solid fa-angle-down small"></i></div>
                      </td>
                    </tr>
                  </table>
                  <div id="vref-selfbalancechanges"></div>
                  <div class="text-end box-footer">
                    <div class="small text-uppercase"><i class="fa-solid fa-angle-left text-muted small"></i>Status : pending<i class="fa-solid fa-angle-right text-muted small"></i> </div>
                  </div>
                </div>
              </div>{{--/end .box--}}


            </td>
          </tr>
          <tr>
            <td>
              <h5>Event flow</h5>
              <h3><i class="fa-solid fa-angle-down"></i></h3>
            </td>
            <td>
              <h5>Event flow</h5>
              <h3><i class="fa-solid fa-angle-down"></i></h3>
            </td>
          </tr>
          {{--event flow: start--}}
          <tr>
            <td><div id="v-eventflow-start"></div></td>
            <td><div id="vref-eventflow-start"></div></td>
          </tr>
          <tr>
            <td><div id="v-eventflow-intermediate"></div></td>
            <td><div id="vref-eventflow-intermediate"></div></td>
          </tr>
          <tr>
            <td><div id="v-eventflow-end"></div></td>
            <td><div id="vref-eventflow-end"></div></td>
          </tr>
        </table>

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
@push('head')
<script src="/res/lib/countup/dist/countUp.min.js" type="module"></script>
{{--<script src="https://unpkg.com/counterup2@2.0.2/dist/index.js"></script>
https://jsfiddle.net/q9CuK/125/
--}}
<style>
.box {
  border:solid 1px #58829b;
  background-color:#0f1c24c9;
  -webkit-border-bottom-right-radius: 15px;
  -moz-border-radius-bottomright: 15px;
  border-bottom-right-radius: 15px;
}
.box-title {
  border: solid 1px #29404f;
  background-color:#29404f82;
  margin:3px;
  -webkit-border-bottom-right-radius: 15px;
  -moz-border-radius-bottomright: 15px;
  border-bottom-right-radius: 15px;
}
.box-title .text-muted{
  color:#ffe300 !important
}
.box .table{margin:0}
</style>

@endpush
@push('javascript')
@if($hash)

<script>
var formatted_currencies = {};
function formatPrice(data)
{
  var positive = !(data.value.slice(0, 1) === '-');
  var r = '<table class="table table-sm table-borderless p-1 price text-light"><tr>';
    r += '<td width="50%" align="right" valign="top" class="text-'+(positive?'lime':'red')+'" id="">'+data.value+'</td>';
    r += '<td align="left" valign="middle">'+formatCurrency(data.currency);
    if(data.counterparty) {
      r += '<div class="font-monospace small text-muted">'+xrpaddress_to_short(data.counterparty)+'</div>';
    }
    r += '</td>';
  r += '</tr></table>';
  return r;
  return '<span class="text-'+(positive?'lime':'red')+'">'+(positive?'+':'')+''+data.value+' '+formatCurrency(data.currency)+'<span> '+(data.counterparty? xrpaddress_to_short(data.counterparty):'');
}

function formatCurrency(currency) {
  if(!formatted_currencies[currency])
    return '<span title="'+currency+'">'+currency+'</span>';
  return '<span title="'+currency+'">'+formatted_currencies[currency]+'</span>';
}
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
  if(p.eventFlow.start) {
    var eventflow = '<div class="box mx-3">';
    eventflow += '<div class="box-title text-start p-1"><span class="text-uppercase text-muted">Start <i class="fa-solid fa-angle-right text-muted small"></i></span> <span class="small">'+p.eventFlow.start.account+'</span></div>';
    eventflow += '<div>'+formatPrice(p.eventFlow.start.mutation)+'</div>';
    eventflow += '</div>';
    $("#v"+suffix+"-eventflow-start").html(eventflow);
  }
  if(p.eventFlow.intermediate) {
    var eventflow = '<div class="box mx-3">';
    eventflow += '<div class="box-title text-start p-1"><span class="text-uppercase text-muted">Intermediate <i class="fa-solid fa-angle-right text-muted small"></i></span> <span class="small">'+p.eventFlow.intermediate.account+'</span></div>';
    if(p.eventFlow.intermediate.mutations) {
      if(p.eventFlow.intermediate.mutations.in) {
        eventflow += '<div class="text-uppercase text-muted text-center small"><i class="fa-solid fa-angle-left text-muted small"></i>In<i class="fa-solid fa-angle-right text-muted small"></i></div>';
        eventflow += formatPrice(p.eventFlow.intermediate.mutations.in);
      }
      if(p.eventFlow.intermediate.mutations.out) {
        eventflow += '<div class="text-uppercase text-muted text-center small"><i class="fa-solid fa-angle-left text-muted small"></i>Out<i class="fa-solid fa-angle-right text-muted small"></i></div>';
        eventflow += formatPrice(p.eventFlow.intermediate.mutations.out);
      }
    } else {
      alert('no int. mutations');
    }
    //eventflow += p.eventFlow.end.mutation.value+" "+p.eventFlow.end.mutation.currency;
    eventflow += '</div>';
    $("#v"+suffix+"-eventflow-intermediate").html(eventflow);
  }
  if(p.eventFlow.end) {
    var eventflow = '<div class="box mx-3">';
    eventflow += '<div class="box-title text-start p-1"><span class="text-uppercase text-muted">End <i class="fa-solid fa-angle-right text-muted small"></i></span> <span class="small">'+p.eventFlow.end.account+'</span></div>';
    eventflow += '<div>'+formatPrice(p.eventFlow.end.mutation)+'</div>';
    eventflow += '</div>';
    $("#v"+suffix+"-eventflow-end").html(eventflow);
  }

  //Start animations:
  /*window.counterUp.default( $("#test123")[0], {
      duration: 1000,
      delay: 16,
  } );*/

  var demo = new countUp.CountUp('test123', 100);

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