{{--
  @param string $title
  @param string $suffix
--}}
<div class="box mb-3">
  <div class="box-title text-start p-1">
    <span class="text-uppercase text-muted "><i class="fa-solid fa-angle-left small"></i><span id="v{{$suffix}}-type">...</span><i class="fa-solid fa-angle-right text-muted small"></i></span>
    <span id="v{{$suffix}}-self"></span> <span id="v{{$suffix}}-fee" class="text-center text-danger"></span>
    <div  class="badge rounded-pill text-bg-success float-end text-uppercase">{{$title}}</div>
  </div>

  <div class="p-1 text-start">
    
    <table class="table table-borderless table-sm mb-0">
      <tr>
        <td width="50%" valign="bottom">
          <div class="text-uppercase text-muted text-end small">Events <i class="fa-solid fa-angle-right text-muted small"></i> Value <i class="fa-solid fa-angle-down small"></i></div>
        </td>
        <td width="50%" valign="bottom">
          <div class="text-uppercase text-muted text-start small">Currency <i class="fa-solid fa-angle-down small"></i></div>
        </td>
      </tr>
    </table>
    <div id="v{{$suffix}}-selfevents"></div>
    <table class="table table-borderless table-sm mb-0">
      <tr>
        <td width="50%" valign="bottom">
          <div class="text-uppercase text-muted text-end small">Balance changes <i class="fa-solid fa-angle-right text-muted small"></i> Value <i class="fa-solid fa-angle-down small"></i></div>
        </td>
        <td width="50%" valign="bottom">
          <div class="text-uppercase text-muted text-start small">Currency <i class="fa-solid fa-angle-down small"></i></div>
        </td>
      </tr>
    </table>
    <div id="v{{$suffix}}-selfbalancechanges"></div>
    {{--<div class="text-end box-footer">
      <div class="small text-uppercase"><i class="fa-solid fa-angle-left text-muted small"></i>Status : <span id="v{{$suffix}}-status">pending</span><i class="fa-solid fa-angle-right text-muted small"></i> </div>
    </div>--}}
  </div>
</div>{{--/end .box--}}