@extends('layouts.mainmaster')
@section('page_heading',$train->trainName)
@section('section')

   

 <div class="col-sm-10 col-sm-offset-1">
 
  
     

                <div class="col-sm-8 col-sm-offset-2">
                    
                <a class="col-sm-1" href="?currdate={{date('Ymd',strtotime('-1month',$currdate))}}">
                <h2 class="text-center"><i class="fa fa-angle-double-left" aria-hidden="true"></i></h2>
                    </a>
                    <a class="col-sm-1" href="?currdate={{date('Ymd',strtotime('-1day',$currdate))}}">
                <h2 class="text-center"><i class="fa fa-angle-left" aria-hidden="true"></i></h2>
                    </a>
                <a class="col-sm-8">
                <h2 class="text-center">{{date("l jS \of F Y",$currdate)}}</h2> 
                    </a>
                <a class="col-sm-1" href="?currdate={{date('Ymd',strtotime('+1day',$currdate))}}">
                <h2 class="text-center"><i class="fa fa-angle-right" aria-hidden="true"></i></h2></a>
                <a class="col-sm-1" href="?currdate={{date('Ymd',strtotime('+1month',$currdate))}}" >
                <h2 class="text-center"><i class="fa fa-angle-double-right" aria-hidden="true"></i></h2></a>

</div>
                    <div class="col-sm-8 col-sm-offset-2">
  <table class="table table-bordered table-striped">
    <thead>
      <tr class="info">
        <th class="text-center">No</th>
        <th>Name</th>
        <th class="text-center">Morning Session</th>
        <th class="text-center">Evening Session</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
         @if (count($attnd) > 0)
                                        
                                            @foreach ($attnd as $key=>$attnd)
         <tr>
        <td class="text-center">{{$key+1}}</td>
        <td>
        <a href="{{url('trneeattendance/'.encrypt($attnd->id))}}"  >
        {{strtoupper($attnd->empname)}}
        </a>
        </td>
        <td class="text-center">{{$attnd->in}}</td>
        <td class="text-center">{{$attnd->out}}</td>
        <td>
        <form action="{{ url('checkin_attnd') }}" id="checkin" method="POST">
                            {{ csrf_field() }} 
                            <input type="hidden" name="id" value="{{$attnd->id}}">
                            <button  class="btn btn-block btn-primary"  type="submit"><i class="fa fa-check "></i> CheckIn</button>

                            </form>
                            <a data-toggle="modal" href="#" data-target="#addAttnd"  data-id="{{$attnd->id}}"  data-name="{{$attnd->empname}}" class="btn btn-block btn-primary">
                            <i class="fa fa-clock-o "></i>
                            Key In</a>
        </td>
      </tr>
                                        
                                        @endforeach
                                        @endif
     
      
    </tbody>
  </table>
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
                    <h3 class="modal-title" id="trneeName"></h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                
            <div class="col-sm-10 col-sm-offset-1">  
                <form class="form-horizontal" action="{{url('addattnd')}}" id="record" method="post">
                {{ csrf_field() }}
                      <input type="hidden" name="id">
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
   let id = button.data('id') // Extract info from data-* attributes
   let name = button.data('name') // Extract info from data-* attributes

   var now = new Date();
    var offset = now.getTimezoneOffset() * 60000;
    var adjustedDate = new Date(now.getTime() - offset);
     let time = adjustedDate.toISOString().substring(0,16)

    
//   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
//   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('[name="id"]').val(id);
  modal.find('[name="attenddate"]').val(time);
  modal.find('[id="trneeName"]').text(name);
})


</script>
@endsection