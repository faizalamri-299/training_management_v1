@extends('layouts.mainmaster')
@section('page_heading','Desktop')
@section('section')

   

 <div class="col-sm-10 col-sm-offset-1">
     <div class="panel panel-primary panelbutton">
		<div class="panel-heading" style="padding:0;">
			<p class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" class="btn btn-lg collapsed btn-block">
			<i class="fa fa-plus-circle" aria-hidden="true"></i>
	Add Training
			</a>
		</p>
	</div>
	<div id="collapse1" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
		<div class="panel-body">
            
                   
            <form action="{{url('trainadd')}}" method="POST">
                {{ csrf_field() }}
                <div class="col-sm-6">
            <div class="form-group"><h4>
                <label for="sel1">Course</label>
                <select class="form-control" id="sel1" name="courseFK">
    <option disabled selected value> -- select an option -- </option>
                @foreach ($course as $key=>$course)
       <option value="{{$course->coursePK}}">{{$course->courseName}}</option>
        @endforeach
                </select>
                </h4>
                
                    </div> </div>

                      <div class="col-sm-6">
            <div class="form-group"><h4>
                <label for="sel2">State</label>
                <select class="form-control" id="sel2" name="trainState">
                
    <option disabled selected value> -- select an option -- </option>
                @foreach ($state as $key=>$state)
       <option value="{{$state->statePK}}">{{$state->stateName}}</option>
        @endforeach
                </select>
                </h4>
                
                    </div> </div>
                    <div class="col-sm-6">
            <div class="form-group"><h4>
                <label>Training Name</label>
                <input class="form-control" name="trainName" type="text" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> </div>
                <div class="col-sm-6">
                    <div class="form-group"><h4>
                <label>Training Place</label>
                <input class="form-control" name="trainPlace" type="text" placeholder="Dewan Al-Marbawiy-UTM" required></h4>
                
            </div></div>
            <div  class="col-sm-6">
            <div class="col-sm-6">
		<div class="form-group"><h4>
                <label>Start Date</label><br>
                 <input id="datestart" name="trainDtStart" class="form-control" type="date" placeholder="" required></h4></div>
	</div>
            <div class="col-sm-6">
		<div class="form-group"><h4>
                <label>End Date</label><br>
           <input id="dateend" name="trainDtEnd" class="form-control" type="date" placeholder="31.12.9999" required></h4></div>
           
        </div>      
        <div class="col-sm-12">
            <div class="form-group"><h4>
                <label>Training Mode</label>
                
               
<label class="radio-inline"><input type="radio" name="isFulltime" value="1">Full Time</label>
<label class="radio-inline"><input type="radio" name="isFulltime" value="0">Part Time</label>
                    </div> </div>  
</div>
                 <div class="col-sm-6">
                        <div class="form-group">
                            <h4>
                                <label>Address</label>
                                <textarea class="form-control"  rows="4"  name="trainAddress" placeholder="Eg: Required company registration" required></textarea>
                            </h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <h4>
                                <label>Details</label>
                                <textarea id="summernote" class="form-control"  rows="4"  name="trainDetail" placeholder="Eg: Required company registration" required></textarea>
                            </h4>
                        </div>
                    </div>
                    <button class="button button--large" type="submit">Submit</button>
            </form>
		</div>
	</div>
</div>
     
@if (count($year) > 0)
<ul class="nav nav-tabs nav-justified">

@foreach ($year as $key=>$year)
    <li class='{{$year->year == date("Y") ? "active" : ""}}'><a data-toggle="tab" href="#{{$year->year}}">{{$year->year}}</a></li>
@endforeach
  </ul>
@endif

@if (count($data) > 0)

<div class="tab-content">
    @foreach ($data as $key=>$data2)
    <div id="{{$data2->year}}" class='tab-pane fade {{$data2->year == date("Y") ? "in active" : ""}}'>
 
    @if(count($data2->train)>0)
    
  <table class="table table-striped">
    <thead>
      <tr class="info">
        <th class="text-center">No</th>
        <th class="text-center">Training</th>
        <th class="text-center">Trainee</th>
        <th class="text-center">Place</th>
        <th class="text-center">Date Start</th>
        <th class="text-center">Date End</th>
        
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>

    @foreach ($data2->train as $key=>$trainitem)

    <tr class="">
        <td class="text-center">{{$key+1}}</td>
        <td class="text-center">
              <form action="{{url('training/'.encrypt($trainitem->trainPK)) }}" method="GET">
            <button class="btn-link">{{$trainitem->trainName}}</button>
        </form>
             </td>
        <td class="text-center">{{$trainitem->trneecnt}}</td>
        <td class="text-center">{{$trainitem->trainPlace}}</td>
        <td class="text-center">{{$trainitem->trainDtStart}}</td>
        <td class="text-center">{{$trainitem->trainDtEnd}}</td>
        <td>
            <button type="button" data-toggle="modal"
                data-target="#edtTrain" data-trainid="{{$trainitem->trainPK}}"
                data-coursefk="{{$trainitem->courseFK}}"
                data-trainname="{{$trainitem->trainName}}"
                data-traindetail="{{$trainitem->trainDetail}}"
                data-trainplace="{{$trainitem->trainPlace}}"
                data-traindiscnt="{{$trainitem->trainDiscnt}}"
                data-trainaddress="{{$trainitem->trainAddress}}"
                data-trainstate="{{$trainitem->trainState}}"
                data-trainstart="{{$trainitem->trainDtStart}}"
                data-trainend="{{$trainitem->trainDtEnd}}"
                data-isfulltime="{{$trainitem->isFulltime}}"
                class="btn btn-warning btn-circle btn-sm">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </button>
        </td>
        <td>
        <form action="{{ url('deletetraining') }}" method="POST" class="dropdown-item btn-block delete ">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <input type="hidden" name="pk" value="{{$trainitem->trainPK}}" />
                            <button  class="btn btn-block btn-danger"  type="submit"><i class="fa fa-trash "></i></button>
                        </form>
        </td>
      </tr>
    @endforeach

    </tbody>
    </table>
    @endif
    </div>
    @endforeach
</div>
@endif
         <!-- @if (count($train) > 0)
  
  <table class="table table-striped">
    <thead>
      <tr class="info">
        <th class="text-center">No</th>
        <th class="text-center">Training</th>
        <th class="text-center">Trainee</th>
        <th class="text-center">Place</th>
        <th class="text-center">Date Start</th>
        <th class="text-center">Date End</th>
        
        <th></th>
      </tr>
    </thead>
    <tbody>

        @foreach ($train as $key=>$train)
         <tr class="">
        <td class="text-center">{{$key+1}}</td>
        <td class="text-center">
              <form action="{{url('training/'.encrypt($train->trainPK)) }}" method="GET">
            <button class="btn-link">{{$train->trainName}}</button>
        </form>
             </td>
        <td class="text-center">{{$train->trneecnt}}</td>
        <td class="text-center">{{$train->trainPlace}}</td>
        <td class="text-center">{{$train->trainDtStart}}</td>
        <td class="text-center">{{$train->trainDtEnd}}</td>
        <td>
            <button type="button" data-toggle="modal"
                data-target="#edtTrain" data-trainid="{{$train->trainPK}}"
                data-coursefk="{{$train->courseFK}}"
                data-trainname="{{$train->trainName}}"
                data-traindetail="{{$train->trainDetail}}"
                data-trainplace="{{$train->trainPlace}}"
                data-traindiscnt="{{$train->trainDiscnt}}"
                data-trainaddress="{{$train->trainAddress}}"
                data-trainstate="{{$train->trainState}}"
                data-trainstart="{{$train->trainDtStart}}"
                data-trainend="{{$train->trainDtEnd}}"
                data-isfulltime="{{$train->isFulltime}}"
                class="btn btn-warning btn-circle btn-sm">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </button>
        </td>
      </tr>
        @endforeach
    </tbody>
  </table>
     
        @endif -->
</div>



<div class="modal fade qs-item-modal-lg" id="edtTrain" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Edit Course</h4>
                </div>
                
               
                <form action="{{url('trainupdate')}}" method="POST">
                {{ csrf_field() }}

                
                <input type="hidden" name="trainPK" value="">

                <div class="col-sm-6">
            <div class="form-group"><h4>
                <label for="sel1">Course</label>
                <select class="form-control" id="sel1" name="courseFK">
    <option disabled selected value> -- select an option -- </option>
                @foreach ($course2 as $key=>$course)
       <option value="{{$course->coursePK}}">{{$course->courseName}}</option>
        @endforeach
                </select>
                </h4>
                
                    </div> </div>

                      <div class="col-sm-6">
            <div class="form-group"><h4>
                <label for="sel2">State</label>
                <select class="form-control" id="sel2" name="trainState">
                
    <option disabled selected value> -- select an option -- </option>
                @foreach ($state2 as $key=>$state)
       <option value="{{$state->statePK}}">{{$state->stateName}}</option>
        @endforeach
                </select>
                </h4>
                
                    </div> </div>
                    <div class="col-sm-6">
            <div class="form-group"><h4>
                <label>Training Name</label>
                <input class="form-control" name="trainName" type="text" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> </div>
                <div class="col-sm-6">
                    <div class="form-group"><h4>
                <label>Training Place</label>
                <input class="form-control" name="trainPlace" type="text" placeholder="Dewan Al-Marbawiy-UTM" required></h4>
                
            </div></div>
            <div  class="col-sm-6">
            <div class="col-sm-6">
		<div class="form-group"><h4>
                <label>Start Date</label><br>
                 <input id="datestart" name="trainDtStart" class="form-control" type="date" placeholder="" required></h4></div>
	</div>
            <div class="col-sm-6">
		<div class="form-group"><h4>
                <label>End Date</label><br>
           <input id="dateend" name="trainDtEnd" class="form-control" type="date" placeholder="31.12.9999" required></h4></div>
           
        </div>      
        <div class="col-sm-12">
            <div class="form-group"><h4>
                <label>Training Mode</label>
                
               
<label class="radio-inline"><input id="ck1" type="radio" name="isFulltime" value="1">Full Time</label>
<label class="radio-inline"><input id="ck2" type="radio" name="isFulltime" value="0">Part Time</label>
                    </div> </div>  
</div>
                 <div class="col-sm-6">
                        <div class="form-group">
                            <h4>
                                <label>Address</label>
                                <textarea class="form-control"  rows="4"  name="trainAddress" placeholder="Eg: Required company registration" required></textarea>
                            </h4>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <h4>
                                <label>Details</label>
                                <textarea id="trainDetail" class="form-control"  rows="4"  name="trainDetail" placeholder="Eg: Required company registration" required></textarea>
                            </h4>
                        </div>
                    </div>
                    <button class="button button--large" type="submit">Submit</button>
            </form>
            </div>
        </div>
    </div>

    @endsection


@section('page-script')
<script>


$(".delete").on("submit", function(){
        return confirm("Are you sure to delete this item?");
    });

</script>
@endsection