@extends('layouts.mainmaster')
@section('page_heading',$train->trainName)
@section('section')

@php
$dayinmonth = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'))
@endphp

 <div class="col-sm-10 col-sm-offset-1">
 <img  class="center showprint" src="{{ asset('./logo.png') }}"/>
 <h2 class="text-center showprint">{{$train->courseName}}</i></h2>
 <h4 class="text-center showprint">{{date('d/m/Y',strtotime( $train->trainDtStart)).' - '.date('d/m/Y',strtotime( $train->trainDtEnd))}}</i></h4>
 <!-- <h5 class="text-center">{{$train->trainPlace}}</i></h5> -->

 <h2 class="text-center">{{$trnee->trneeName}}</i></h2>
 <h4 class="text-center">{{$trnee->trneeIcNo}}</i></h4>
 <h5 class="text-center">{{$trnee->trneeCmpny}}</i></h5>

                    <div class="col-sm-8 col-sm-offset-2">
                    @if (!$hrdf)
                    <a href="{{url('trneeattendance/'.encrypt($trnee->tnee_trainPK).'/hrdf')}}"   class="dropdown-item btn btn-block btn-primary hideprint" type="button">
                    Hide Second Session
                    </a>
                    @else
                    <a href="{{url('trneeattendance/'.encrypt($trnee->tnee_trainPK))}}"   class="dropdown-item btn btn-block btn-primary hideprint" type="button">
                    Attendance
                    </a>
                    @endif
                    <button type="button" data-toggle="modal" data-target="#addAttnd"  class="btn btn-lg btn-primary btn-block hideprint" type="button">
     <!-- <img src="..." alt="..."> -->
         <i class="fa fa-check-circle" aria-hidden="true"></i>
        Add Record
    </button>
                    <table class="table table-bordered table-striped">
    <thead>
      <tr class="info">
        <th class="text-center">Date</th>
        <th class="text-center">
        @if (!$hrdf)
        First
        @else
        Time
        @endif
        </th>
        @if (!$hrdf)
        <th class="text-center">Second</th>
        @endif
      </tr>
    </thead>
    <tbody>
         @if (count($attnd) > 0)
        @foreach ($attnd as $key=>$attnd)
         <tr class="{{ $attnd->dayindex === '5'||$attnd->dayindex === '6' ? 'danger' : 'dafault' }}">
        <td class="text-center">{{$attnd->dates}}</td>
        <td class="text-center">
        <a data-toggle="modal" href="#" data-target="#addAttnd"  data-pk="{{$attnd->inPK}}"  data-time="{{$attnd->inData}}">{{$attnd->in}}</a>
        </td>
        @if (!$hrdf)
        <td class="text-center">
        <a data-toggle="modal" href="#" data-target="#addAttnd" data-pk="{{$attnd->outPK}}"  data-time="{{$attnd->outData}}">{{$attnd->out}}</a></td>
      </tr>
      
      @endif
        @endforeach
        @endif
    </tbody>
  </table>
  
 <h6 class="text-left showprint">* This is system generated attendance record no signature required.</i></h6>
                </div>
     
     
</div>


<div class="modal fade " id="addAttnd" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Attendance Record</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                
            <div class="col-sm-10 col-sm-offset-1">  
                <form class="form-horizontal" action="{{url('addattnd')}}" id="record" method="post">
                {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{$trnee->tnee_trainPK}}">
                      <input type="hidden" name="pk">
						<div class="form-group">
							<label for="name" class="cols-sm-2 control-label">Input Datetime</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
									<input type="datetime-local" class="form-control" name="attenddate" id="icno"
                    min="{{$train->trainDtStart}}" max="{{$train->trainDtEnd}}"
                   placeholder="record datetime"/>
								</div>
							</div>
						</div>
                            <div class="form-group ">
                                
                    <button class="button button--large" type="submit">Submit</button>
						</div>
                    </form>
                
            </div>
                    </div>
    </div>
            </div>
        </div>
</div>

@endsection

@section('page-script')
<script>


$('#addAttnd').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
   let pk = button.data('pk') // Extract info from data-* attributes
   let time = button.data('time') // Extract info from data-* attributes
if(!time){
   var now = new Date();
    var offset = now.getTimezoneOffset() * 60000;
    var adjustedDate = new Date(now.getTime() - offset);
     time = adjustedDate.toISOString().substring(0,16)

    }
//   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
//   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('[name="pk"]').val(pk)
  modal.find('[name="attenddate"]').val(time)
})


</script>
@endsection