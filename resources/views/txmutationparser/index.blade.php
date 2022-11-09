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
      <button class="nav-link" id="parsed1-tab" data-bs-toggle="tab" data-bs-target="#parsed1-tab-pane" type="button" role="tab" aria-controls="parsed1-tab-pane" aria-selected="false"><i class="fa-solid fa-code"></i> Parsed (left)</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link d-none" id="parsed2-tab" data-bs-toggle="tab" data-bs-target="#parsed2-tab-pane" type="button" role="tab" aria-controls="parsed2-tab-pane" aria-selected="false"><i class="fa-solid fa-code"></i> Parsed (right)</button>
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

        <table class="table table-borderless table-sm text-light">
          <tr>
            <td width="50%">
              @include('txmutationparser.components.main_box',['title' => 'Ref 1 (Initiator)', 'suffix' => 'ref1'])
              <h5>Event flow</h5>
              <h3><i class="fa-solid fa-angle-down"></i></h3>
              <div id="vref1-eventflow-start"></div>
              <div id="vref1-eventflow-intermediate"></div>
              <div id="vref1-eventflow-end"></div>
            </td>
            <td width="50%" id="event_list">
              Waiting...
            </td>
          </tr>
        </table>

      </div>

    </div>
    <div class="tab-pane fade" id="parsed1-tab-pane" role="tabpanel" aria-labelledby="parsed1-tab" tabindex="0">
      <pre id="parsed1-tab-pane-json-display" class="rounded p-4"></pre>
    </div>
    <div class="tab-pane fade" id="parsed2-tab-pane" role="tabpanel" aria-labelledby="parsed2-tab" tabindex="0">
      <pre id="parsed2-tab-pane-json-display" class="rounded p-4"></pre>
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
var tx;
var formatted_currencies = {};
function formatPrice(data,id,refaccount)
{
  var positive = !(data.value.slice(0, 1) === '-');
  var decimals = 0;
  var currency = formatCurrency(data.currency);

    if(data.value.toString().split('.')[1])
      decimals = Number(data.value.toString().split('.')[1]).toString().length;

  var r = '<table class="table table-sm table-borderless p-1 text-light"><tr>';
    r += '<td width="50%" align="right" valign="top" class="py-0 font-monospace price text-'+(positive?'lime':'red')+'" id="price_'+id+'_'+data.currency+(data.counterparty?data.counterparty:'XRP')+refaccount+'" data-decimals="'+decimals+'" data-value="'+data.value+'">'+data.value+'</td>';
    r += '<td align="left" valign="middle" class="py-0"><div class="d-flex"><span title="'+data.currency+'">'+currency+'</span>';
    r += '<span class="font-monospace small text-muted ms-1">';
    if(data.counterparty) {
      if (typeof data.counterparty === 'string' || data.counterparty instanceof String) {
        r += data.counterparty ? xrpaddress_to_short(data.counterparty):'';
      } else if(Array.isArray(data.counterparty )) {
        $.each(data.counterparty, function(k,v){
          r += xrpaddress_to_short(v)+'<br>';
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
function addToAnimationQueueNew(data)
{
  animation_queue.push(data);
}
function addToAnimationQueue(data)
{
  //animation_queue.push(data);
}
function runAnimationQueue()
{
  $("#event_list").html('');
  //#event_list
  //console.log(animation_queue);
  $.each(animation_queue, function(k,v){
    console.log(v);
    setTimeout(function() {
      var r = '<div>';

      r += v.bc.counterparty+' '+v.bc.value;

      r += '</div>';
      $("#event_list").append(r);
    },(v.step*500));
  });

  return;
  $(".price").text('?');
  console.log(animation_queue);
  //step 1
  $.each(animation_queue, function(k,v){

    setTimeout(function() { 
      var _c = '';
      if(v.context) _c = v.context.currency+(v.context.counterparty?v.context.counterparty:'XRP')+v.ref;
      if(v.name == 'start') {
        //todo display start box
        var c = new countUp.CountUp(
          'price_start_mutation'+v.suffix+'_'+_c,
          $("#price_start_mutation"+v.suffix+"_"+_c).data('value'),
          {decimalPlaces: $("#price_start_mutation"+v.suffix+"_"+_c).data('decimals')}
        );
        c.start();

        if($('#price_bc'+v.suffix+'_'+_c).length) {
          var c2 = new countUp.CountUp(
            'price_bc'+v.suffix+'_'+_c,
            $("#price_bc"+v.suffix+"_"+_c).data('value'),
            {decimalPlaces: $("#price_bc"+v.suffix+"_"+_c).data('decimals')}
          );
          c2.start();
        }
        
      }

      if(v.name == 'intermediate') {
        //todo display intermediate box
      }

      if(v.name == 'intermediate_mutation_in') {
        var c = new countUp.CountUp(
          'price_intermediate_mutation_in'+v.suffix+'_'+_c,
          $("#price_intermediate_mutation_in"+v.suffix+"_"+_c).data('value'),
          {decimalPlaces: $("#price_intermediate_mutation_in"+v.suffix+"_"+_c).data('decimals')}
        );
        c.start();

        if($('#price_bc'+v.suffix+'_'+_c).length) {
          var c2 = new countUp.CountUp(
            'price_bc'+v.suffix+'_'+_c,
            $("#price_bc"+v.suffix+"_"+_c).data('value'),
            {decimalPlaces: $("#price_bc"+v.suffix+"_"+_c).data('decimals')}
          );
          c2.start();
        }
      }

      if(v.name == 'intermediate_mutation_out') {
        var c = new countUp.CountUp(
          'price_intermediate_mutation_out'+v.suffix+'_'+_c,
          $("#price_intermediate_mutation_out"+v.suffix+"_"+_c).data('value'),
          {decimalPlaces: $("#price_intermediate_mutation_out"+v.suffix+"_"+_c).data('decimals')}
        );
        c.start();

        if($('#price_bc'+v.suffix+'_'+_c).length) {
          var c2 = new countUp.CountUp(
            'price_bc'+v.suffix+'_'+_c,
            $("#price_bc"+v.suffix+"_"+_c).data('value'),
            {decimalPlaces: $("#price_bc"+v.suffix+"_"+_c).data('decimals')}
          );
          c2.start();
        }
      }

      if(v.name == 'end') {
        //todo display start box
        var c = new countUp.CountUp(
          'price_end_mutation'+v.suffix+'_'+_c,
          $("#price_end_mutation"+v.suffix+"_"+_c).data('value'),
          {decimalPlaces: $("#price_end_mutation"+v.suffix+"_"+_c).data('decimals')}
        );
        c.start();

        if($('#price_bc'+v.suffix+'_'+_c).length) {
          var c2 = new countUp.CountUp(
            'price_bc'+v.suffix+'_'+_c,
            $("#price_bc"+v.suffix+"_"+_c).data('value'),
            {decimalPlaces: $("#price_bc"+v.suffix+"_"+_c).data('decimals')}
          );
          c2.start();
        }
      }

      if(v.name === 'fee' && v.suffix === '' && tx.raw.Fee) {
        //apply fee
        $("#v-status").text('Charging '+tx.raw.Fee+' drops fee').addClass('text-warning');
        var c = new countUp.CountUp('price_bc_XRPXRP', $('#price_bc_XRPXRP').data('value'), {decimalPlaces: $('#price_bc_XRPXRP').data('decimals'), startVal: $('#price_bc_XRPXRP').text()});
        c.start();
      }

      if(v.name == 'complete') {
        $("#v-status").text('Complete').removeClass('text-warning').addClass('text-lime');
      }

    }, (v.step*2600));
  });
}

//test this todo: http://playground.test/play/xrpl-transaction-mutation-parser?hash=284A52AE29D0A6B69822AEFBAE68D2C900E2B516E645BF6DD62536E0BF6EBF24&ref1=rfsK8pNsNeGA8nYWM3PzoRxMRHeAyEtNjN&ref2=rBHdammEERq7nxvHkzRzCUu872k3uQYVvg

function visualize(suffix,data,p)
{
  
  $("#v"+suffix+"-self").text(p.self.account);
  $("#v"+suffix+"-type").text(p.type);
  //Events primary and secondary:
  if(p.eventList.primary) {
    var ev_primary = '<div>'+formatPrice(p.eventList.primary,'bc'+suffix,p.self.account)+'</div>';
    $("#v"+suffix+"-selfevents").append(ev_primary);
  }
  if(p.eventList.secondary) {
    var ev_secondary = '<div>'+formatPrice(p.eventList.secondary,'bc'+suffix,p.self.account)+'</div>';
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
    addToAnimationQueue({step:1,suffix:suffix,name:'start',ref:p.eventFlow.start.account,context:p.eventFlow.start.mutation});
  }
  if(p.eventFlow.intermediate && (p.eventFlow.intermediate.mutations.in !== null || p.eventFlow.intermediate.mutations.out !== null)) {
    addToAnimationQueue({step:2,suffix:suffix,name:'intermediate',ref:p.eventFlow.intermediate.account});
    var eventflow = '<div class="box mb-2">';
    eventflow += '<div class="box-title text-start p-1"><span class="text-uppercase text-muted">Intermediate <i class="fa-solid fa-angle-right text-muted small"></i></span> <span class="small">'+p.eventFlow.intermediate.account+'</span></div>';
    if(p.eventFlow.intermediate.mutations) {
      if(p.eventFlow.intermediate.mutations.in) {
        eventflow += '<div class="text-uppercase text-muted text-center small"><i class="fa-solid fa-angle-left text-muted small"></i>In<i class="fa-solid fa-angle-right text-muted small"></i></div>';
        eventflow += formatPrice(p.eventFlow.intermediate.mutations.in,'intermediate_mutation_in'+suffix,p.eventFlow.intermediate.account);
        addToAnimationQueue({step:3,suffix:suffix,name:'intermediate_mutation_in',ref:p.eventFlow.intermediate.account,context:p.eventFlow.intermediate.mutations.in});
      }
      if(p.eventFlow.intermediate.mutations.out) {
        eventflow += '<div class="text-uppercase text-muted text-center small"><i class="fa-solid fa-angle-left text-muted small"></i>Out<i class="fa-solid fa-angle-right text-muted small"></i></div>';
        eventflow += formatPrice(p.eventFlow.intermediate.mutations.out,'intermediate_mutation_out'+suffix,p.eventFlow.intermediate.account);
        addToAnimationQueue({step:3,suffix:suffix,name:'intermediate_mutation_out',ref:p.eventFlow.intermediate.account,context:p.eventFlow.intermediate.mutations.out});
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
    addToAnimationQueue({step:4,suffix:suffix,name:'end',ref:p.eventFlow.end.account,context:p.eventFlow.end.mutation});
    addToAnimationQueue({step:5,suffix:suffix,name:'fee',ref:p.eventFlow.end.account});
    addToAnimationQueue({step:6,suffix:suffix,name:'complete',ref:p.eventFlow.end.account});
  }

  //Queue balance changes
  var i = 0;
  $.each(p.allBalanceChanges,function(k,v){
    addToAnimationQueueNew({step:(i),ref:p.self.account,bc:v});
    i++;
  });

  
  //TEST animation:
  //var demo = new countUp.CountUp('test123', 0.00000012, {decimalPlaces: 8});
  //demo.start()
}
function process_response(data)
{
  new JsonEditor('#parsed1-tab-pane-json-display',data.parsed1,{editable:false});
  if(data.parsed2) {
    new JsonEditor('#parsed2-tab-pane-json-display',data.parsed2,{editable:false});
    $("#parsed2-tab").removeClass('d-none');
  }
    
  new JsonEditor('#tx-tab-pane-json-display',data.raw,{editable:false});
}
function process_participating_accounts(data)
{
  var r = '';
  $.each(data.participating_accounts, function (k,v){
    r += '<a class="badge rounded-pill text-bg-'+(v == "{{$ref1}}" ? 'light':'dark')+' text-decoration-none" href="{{route('play.txmutationparser.index',['hash' => $hash])}}&ref1='+v+'" title="'+v+'">'+xrpaddress_to_short(v)+'</a> ';
  })
  $("#form-participating_accounts1").html(r);
}

$(function(){
  $.ajax({
      type:'GET',
      dataType: "json",
      url: "{!!route('api.tx',['hash' => $hash, 'ref1' => $ref1])!!}",
      data: {},
      success: function(d){
        tx = d;
        formatted_currencies = d.formatted_currencies;
        process_participating_accounts(d);
        $("#v-txtype").text(d.raw.TransactionType);
        $("#visualized-tab-pane-loader").remove();
        $("#visualized-tab-pane-content").removeClass("d-none");
        //visualize left side
        visualize('ref1',d,d.parsed1);
        
        
        //if(d.parsed2) {
          //visualize right side
        //  visualize('ref2',d,d.parsed2);
        //}

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