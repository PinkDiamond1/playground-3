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
            <label class="form-label">View participant:</label>
            <div id="form-participating_accounts1">
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
      <button class="nav-link" id="tx-tab" data-bs-toggle="tab" data-bs-target="#tx-tab-pane" type="button" role="tab" aria-controls="tx-tab-pane" aria-selected="false"><i class="fa-solid fa-code"></i> Raw Transaction</button>
    </li>
  </ul>
  <div class="tab-content mb-5">
    <div class="tab-pane fade show active position-relative" id="visualized-tab-pane" role="tabpanel" aria-labelledby="visualized-tab" tabindex="0" style="min-height:3600px">
      <canvas id="maincanvas" class="position-absolute" width="0" height="0"></canvas>
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
              @include('txmutationparser.components.main_box',['title' => 'Perspective', 'suffix' => 'ref'])
              <h5>Event flow</h5>
              <h3><i class="fa-solid fa-angle-down"></i></h3>
              <div id="vref-eventflow-start"></div>
              <div id="vref-eventflow-intermediate"></div>
              <div id="vref-eventflow-end"></div>
            </td>
            <td width="50%" id="event_list">
              Waiting...
            </td>
          </tr>
        </table>

      </div>

    </div>
    <div class="tab-pane fade" id="parsed-tab-pane" role="tabpanel" aria-labelledby="parsed-tab" tabindex="0">
      <pre id="parsed-tab-pane-json-display" class="rounded p-4"></pre>
    </div>
    <div class="tab-pane fade" id="tx-tab-pane" role="tabpanel" aria-labelledby="tx-tab" tabindex="0">
      <pre id="tx-tab-pane-json-display" class="rounded p-4"></pre>
    </div>
  </div>
</div>
@endsection
@push('head')
<script src="/res/lib/countup/dist/countUp.umd.js"></script>
{{--<script src="https://unpkg.com/counterup2@2.0.2/dist/index.js"></script>
https://jsfiddle.net/q9CuK/125/
--}}
<style>
#maincanvas{
  width:100%;
  height:100%;
  z-index:100;
  pointer-events:none
}
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
.box.box-gray{
  border-color:#2d2e2f;
  background-color:#1b1c1cc9
}
.box.box-gray .box-title{
  border-color:#363839;
  background-color:#36383982
}
#event_list>.box:first-child{
  border-color:#3c2c09;
  background-color:#241900c9
}
#event_list>.box:first-child .box-title{
  border-color:#60470c;
  background-color:#60470c82
}
</style>

@endpush
@push('javascript')
@if($hash)

<script>
var tx,ctx,cc;
var formatted_currencies = {};

function formatPrice(data,id,refaccount)
{
  var positive = !(data.value.slice(0, 1) === '-');
  var decimals = 0;
  var currency = formatCurrency(data.currency);

  if(data.value.toString().split('.')[1]) {
    decimals = data.value.toString().split('.')[1].toString().length;
  }
  var r = '<table class="table table-sm table-borderless p-1 text-light mb-0"><tr>';
    r += '<td width="50%" align="right" valign="top" class="py-0 font-monospace text-'+(positive?'lime':'red')+'"><span class="price" id="price_'+id+'_'+data.currency+(data.counterparty?data.counterparty:'XRP')+refaccount+'" data-decimals="'+decimals+'" data-value="'+data.value+'" title="'+data.value+'">'+data.value+'</span></td>';
    r += '<td align="left" valign="middle" class="py-0"><div class="d-flex"><span title="'+data.currency+'">'+currency+'</span>';
    r += '<span class="font-monospace small text-muted ms-1">';
    if(data.counterparty) {
      if (typeof data.counterparty === 'string' || data.counterparty instanceof String) {
        r += data.counterparty ? ('<div title="'+data.counterparty+'">'+xrpaddress_to_short(data.counterparty)+'</div>'):'';
      } else if(Array.isArray(data.counterparty )) {
        $.each(data.counterparty, function(k,v){
          r += '<div title="'+v+'">'+xrpaddress_to_short(v)+'</div>';
        });
      } else {
        alert('Unhandled counterparty detected');
      }
    }
    
    
    r += '</span></div></td>';
  r += '</tr></table>';
  return r;
}

function formatCurrency(currency) {
  if(!formatted_currencies[currency])
    return currency;
  return formatted_currencies[currency];
}

var animation_queue = [];
function addToAnimationQueue(data)
{
  animation_queue.push(data);
}
function runAnimationQueue()
{
  $(".price").text('?');
  $("#event_list").html('');
  step = 0;
  $.each(animation_queue, function(k,v){
    setTimeout(function() {
      ctx.clearRect(0, 0, cc.width, cc.height);
      $(".pa").removeClass('text-warning');
      $("#pa_"+v.ref).addClass('text-warning');
      var cu = [];
      var parts = ['evref','bcref','start_mutationref','intermediate_mutation_inref','intermediate_mutation_outref','end_mutationref'];
      var r = '<div class="box box-gray mb-1">';
      r += '<div class="box-title text-start p-1"><span class="text-uppercase text-muted "><i class="fa-solid fa-angle-left small"></i>'+tx.parsedall[v.ref].type+'<i class="fa-solid fa-angle-right text-muted small"></i></span> '+v.ref+'</div>';
      r += '<div id="event_list_'+k+'"></div></div>';
      $("#event_list").prepend(r);

      $.each(v.bc.balances, function(kk,vv){
        var _c = vv.currency+(vv.counterparty?vv.counterparty:'XRP')+v.ref;
        var _c_flipped = vv.currency+(vv.counterparty?(v.ref+vv.counterparty):'XRP'+v.ref); //currency counterparty refaccount
        $("#event_list_"+k).append(formatPrice(vv,'eventlist',v.ref));
        cu.push(
          new countUp.CountUp('price_eventlist_'+_c, $('#price_eventlist_'+_c).data('value'), {
            decimalPlaces: $('#price_eventlist_'+_c).data('decimals'),
            endValOriginal: $('#price_eventlist_'+_c).data('value')
          })
        );
        drawLine('price_evref_'+_c_flipped,'price_eventlist_'+_c);
        drawLine('price_bcref_'+_c_flipped,'price_eventlist_'+_c);

        
        $.each(parts,function(partk,partv) {
          if($('#price_'+partv+'_'+_c_flipped).length) {
            drawLine('price_'+partv+'_'+_c_flipped,'price_eventlist_'+_c);
            cu.push(
              new countUp.CountUp('price_'+partv+'_'+_c_flipped, $('#price_'+partv+'_'+_c_flipped).data('value'), {
                decimalPlaces: $('#price_'+partv+'_'+_c_flipped).data('decimals'),
                endValOriginal: $('#price_'+partv+'_'+_c_flipped).data('value')
              })
            );
          }
        });
      });
      //countup animations start:
      $.each(cu, function(a,c){c.start(function(){$("#"+this._target).html(numberWithCommas(this.options.endValOriginal))})});
      
    },(v.step*2500));  //2500
    step = v.step;
  });
  // Finalize
  setTimeout(function() {
    //show any left over '?' prices
    $(".price").each(function(k,v){
      if($(v).text() === '?') {
        $(v).text(numberWithCommas($(v).data('value')));
      }
    })
  },((step+1)*2500));
}
function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}
function reverseObjectIntoArray(obj){
  var array = [];
  for (var i in obj) {
    array.push(obj[i]);
  }
  array.reverse();
  return array;
}
//test this todo: http://playground.test/play/xrpl-transaction-mutation-parser?hash=284A52AE29D0A6B69822AEFBAE68D2C900E2B516E645BF6DD62536E0BF6EBF24&ref1=rfsK8pNsNeGA8nYWM3PzoRxMRHeAyEtNjN&ref2=rBHdammEERq7nxvHkzRzCUu872k3uQYVvg

function visualize(suffix,data,p)
{
  $("#v"+suffix+"-self").text(p.self.account);
  $("#v"+suffix+"-type").text(p.type);
  //Events primary and secondary:
  if(p.eventList.primary) {
    var ev_primary = '<div>'+formatPrice(p.eventList.primary,'ev'+suffix,p.self.account)+'</div>';
    $("#v"+suffix+"-selfevents").append(ev_primary);
  }
  if(p.eventList.secondary) {
    var ev_secondary = '<div>'+formatPrice(p.eventList.secondary,'ev'+suffix,p.self.account)+'</div>';
    $("#v"+suffix+"-selfevents").append(ev_secondary);
  }
  //Balance changes:
  var balchanges = '';
  $.each(p.self.balanceChanges,function(k,v){
    balchanges += '<div>'+formatPrice(v,'bc'+suffix,p.self.account)+'</div>';
  });
  $("#v"+suffix+"-selfbalancechanges").html(balchanges);

  //Event flow:
  if(p.eventFlow.start) {
    var eventflow = '<div class="box mb-2">';
    eventflow += '<div class="box-title text-start p-1"><span class="text-uppercase text-muted">Start <i class="fa-solid fa-angle-right text-muted small"></i></span> <span class="small">'+p.eventFlow.start.account+'</span></div>';
    eventflow += '<div>'+formatPrice(p.eventFlow.start.mutation,'start_mutation'+suffix,p.eventFlow.start.account)+'</div>';
    eventflow += '</div>';
    $("#v"+suffix+"-eventflow-start").html(eventflow);
  }
  if(p.eventFlow.intermediate && (p.eventFlow.intermediate.mutations.in !== null || p.eventFlow.intermediate.mutations.out !== null)) {
    var eventflow = '<div class="box mb-2">';
    eventflow += '<div class="box-title text-start p-1"><span class="text-uppercase text-muted">Intermediate <i class="fa-solid fa-angle-right text-muted small"></i></span> <span class="small">'+p.eventFlow.intermediate.account+'</span></div>';
    if(p.eventFlow.intermediate.mutations) {
      if(p.eventFlow.intermediate.mutations.in) {
        eventflow += '<div class="text-uppercase text-muted text-center small"><i class="fa-solid fa-angle-left text-muted small"></i>In<i class="fa-solid fa-angle-right text-muted small"></i></div>';
        eventflow += formatPrice(p.eventFlow.intermediate.mutations.in,'intermediate_mutation_in'+suffix,p.eventFlow.intermediate.account);
      }
      if(p.eventFlow.intermediate.mutations.out) {
        eventflow += '<div class="text-uppercase text-muted text-center small"><i class="fa-solid fa-angle-left text-muted small"></i>Out<i class="fa-solid fa-angle-right text-muted small"></i></div>';
        eventflow += formatPrice(p.eventFlow.intermediate.mutations.out,'intermediate_mutation_out'+suffix,p.eventFlow.intermediate.account);
      }
    } else {
      alert('no int. mutations');
    }
    //eventflow += p.eventFlow.end.mutation.value+" "+p.eventFlow.end.mutation.currency;
    eventflow += '</div>';
    $("#v"+suffix+"-eventflow-intermediate").html(eventflow);
    
  }
  if(p.eventFlow.end) {
    var eventflow = '<div class="box mb-2">';
    eventflow += '<div class="box-title text-start p-1"><span class="text-uppercase text-muted">End <i class="fa-solid fa-angle-right text-muted small"></i></span> <span class="small">'+p.eventFlow.end.account+'</span></div>';
    eventflow += '<div>'+formatPrice(p.eventFlow.end.mutation,'end_mutation'+suffix,p.eventFlow.end.account)+'</div>';
    eventflow += '</div>';
    $("#v"+suffix+"-eventflow-end").html(eventflow);
  }

  //Queue balance changes
  var i = 0;
  $.each(p.allBalanceChanges,function(k,v){
    //if(k !== '{{$ref}}') {
      addToAnimationQueue({step:i,ref:v.account,bc:v});
      i++;
    //}
  });
  //for: http://playground.test/play/xrpl-transaction-mutation-parser?hash=A357FD7C8F0BBE7120E62FD603ACBE98819BC623D5D12BD81AC68564393A7792&ref=rhub8VRN55s94qWKDv6jmDy1pUykJzF3wq
  //drawLine('price_evref_EURrJWSJ8b2DxpvbhJjTA3ZRiEK2xsxZNHaLPrhub8VRN55s94qWKDv6jmDy1pUykJzF3wq','price_intermediate_mutation_inref_EURrJWSJ8b2DxpvbhJjTA3ZRiEK2xsxZNHaLPrhub8VRN55s94qWKDv6jmDy1pUykJzF3wq');
}
function process_response(data)
{
  new JsonEditor('#parsed-tab-pane-json-display',data.parsed,{editable:false});
  new JsonEditor('#tx-tab-pane-json-display',data.raw,{editable:false});
}
function process_participating_accounts(data)
{
  var r = '';
  $.each(data.participating_accounts, function (k,v){
    r += '<a id="pa_'+v+'" class="pa badge rounded-pill text-bg-'+(v == "{{$ref}}" ? 'light':'dark')+' text-decoration-none" href="{{route('play.txmutationparser.index',['hash' => $hash])}}&ref='+v+'" title="'+v+'">'+xrpaddress_to_short(v)+'</a> ';
  })
  $("#form-participating_accounts1").html(r);
}
//draw line between two dom elements
function drawLine(id1,id2)
{
  var parentPos = document.getElementById('visualized-tab-pane').getBoundingClientRect();
  var cp = document.getElementById(id1);
  if(!cp) return;

  var childPos = cp.getBoundingClientRect();
  var relativePos = {};

  relativePos.top = childPos.top - parentPos.top,
  //relativePos.right = childPos.right - parentPos.right,
  //relativePos.bottom = childPos.bottom - parentPos.bottom,
  relativePos.left = childPos.left - parentPos.left;

  var cp2 = document.getElementById(id2);
  if(!cp2) return;
  var childPos2 = cp2.getBoundingClientRect();
  var relativePos2 = {};

  relativePos2.top = childPos2.top - parentPos.top,
  //relativePos2.right = childPos2.right - parentPos.right,
  //relativePos2.bottom = childPos2.bottom - parentPos.bottom,
  relativePos2.left = childPos2.left - parentPos.left;

  ctx.beginPath();
  ctx.lineWidth = 1;
  ctx.strokeStyle = "#58829b"; // Green path
  ctx.moveTo((relativePos.left+(childPos.right - childPos.left))+3, relativePos.top+10);
  //ctx.lineTo((relativePos2.left+childPos2.left), relativePos2.top+10);
  ctx.lineTo(relativePos2.left-120, relativePos2.top+9);
  //ctx.lineTo((relativePos2.left+(childPos.right - childPos.left)), relativePos2.top+10);
  ctx.stroke();
}


$(function(){
  cc = document.getElementById("maincanvas");
  cc.width = $("#visualized-tab-pane").width();
  cc.height = $("#visualized-tab-pane").height();
  ctx = cc.getContext("2d");

  $.ajax({
      type:'GET',
      dataType: "json",
      url: "{!!route('api.tx',['hash' => $hash, 'ref' => $ref])!!}",
      data: {},
      success: function(d){
        tx = d;
        formatted_currencies = d.formatted_currencies;
        process_participating_accounts(d);
        $("#v-txtype").text(d.raw.TransactionType);
        $("#visualized-tab-pane-loader").remove();
        $("#visualized-tab-pane-content").removeClass("d-none");
        visualize('ref',d,d.parsed);
        process_response(d);
        runAnimationQueue();
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