@extends('layouts.mainmaster')
@section('page_heading',$train->trainName)
@section('section')

   

 <div class="col-sm-10 col-sm-offset-1">
  <div class="panel panel-default">
  <div class="panel-heading"><strong>Training Info</strong></div>
  <div class="panel-body">
     <div class="form-group">
        
            <div class="col-sm-6">
                <div class="text-left">
                    <h5>
                        <label>Training Name</label>
                    </h5>
                    <p>{{strtoupper($train->trainName)}}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="text-left">
                    <h5>
                        <label>Training Place</label>
                    </h5>
                    <p>{{$train->trainPlace}}</p>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="text-left">
                    <h5>
                        <label>Date Start</label>
                    </h5>
                    <p>{{($train->trainDtStart)}}</p>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="text-left">
                    <h5>
                        <label>Date End</label>
                    </h5>
                    <p>{{($train->trainDtEnd)}}</p>
                </div>
            </div>
            
            <div class="col-sm-6">
                <div class="text-left">
                    <h5>
                        <label>Training Address</label>
                    </h5>
                    <p>{{($train->trainAddress)}}</p>
                </div>
            </div>
   
            <form action="{{ url('copytotest') }}" method="POST">
                            {{ csrf_field() }} 
                            <input type="hidden" name="trainPK" value="{{$train->trainPK}}">
                            <button  class="btn btn-block btn-primary"  type="submit"><i class="fa fa-copy "></i> Create Test Course</button>
                        </form>
    </div>
    </div>
</div>
     
     <div class="row">
  <div class="col-xs-6 col-md-3">
  <button type="button" data-toggle="modal" data-target="#addTrainee" class="btn btn-lg btn-primary btn-block">
    <i class="fa fa-plus-circle" aria-hidden="true"></i>
    Add Trainee
</button>
  </div>
  <div class="col-xs-6 col-md-3">
  <button type="button" data-toggle="modal" data-target="#addDiscount" class="btn btn-lg btn-primary btn-block">
    <i class="fa fa-plus-circle" aria-hidden="true"></i>
    Add Discount
</button>
  </div>
  <div class="col-xs-6 col-md-3">
    <a href="{{url('attendance/'.encrypt($train->trainPK)) }}"   class="btn btn-lg btn-primary btn-block" type="button">
     <!-- <img src="..." alt="..."> -->
         <i class="fa fa-list" aria-hidden="true"></i>
        Trainee Attendance
    </a>
  </div>
    
  <div class="col-xs-6 col-md-3">
      
    <button type="button" data-toggle="modal" data-target="#traningPunch"  class="btn btn-lg btn-primary btn-block" type="button">
     <!-- <img src="..." alt="..."> -->
         <i class="fa fa-check-circle" aria-hidden="true"></i>
        Punch Attendance
    </button>
  </div>
    
</div>
     
        @if (count($discnt) > 0)
  <div class="panel panel-default">
  <div class="panel-heading"><strong>Discount Info</strong></div>
  <div class="panel-body">
  <table class="table table-striped">
    <thead>
      <tr class="info">
        <th class="text-center">No</th>
        <th class="text-center">Discount %</th>
        <th class="text-center">Code</th>
        <th class="text-center">isAuto</th>
        <th class="text-center">From</th>
        <th class="text-center">To</th>
        <th class="text-center"></th>
      </tr>
    </thead>
    <tbody>

        @foreach ($discnt as $key=>$discnt)
         <tr class="">
        <td class="text-center">{{$key+1}}</td>
        <td class="text-center">{{$discnt->discntVal}}</td>
        <td class="text-center">{{$discnt->discntCode}}</td>
        <td class="text-center">{{$discnt->discntAuto}}</td>
        <td class="text-center">{{$discnt->discntFrom}}</td>
        <td class="text-center">{{$discnt->discntTo}}</td>
        <td>
        <form action="{{ url('deletediscount') }}" method="POST" class="dropdown-item btn-block delete ">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <input type="hidden" name="pk" value="{{$discnt->discntPK}}" />
                            <button  class="btn btn-block btn-danger"  type="submit"><i class="fa fa-trash "></i> Delete</button>
                        </form>
        </td>
      </tr>
        @endforeach
    </tbody>
  </table>
     
        
    </div>
</div>
     @endif

       @if (count($trainee) > 0)
  <div class="panel panel-default">
  <div class="panel-heading"><strong>Training Info</strong></div>
  <div class="panel-body">
  <table class="table table-responsive table-sm">
    <thead>
      <tr class="background-info">
        <th class="text-center">No</th>
        <th class="text-center"></th>
        <th class="text-center">Participant Info</th>
        <th class="text-center">Company Info</th>
        <th class="text-center">Contact Info</th>
        <th class="text-center">Training Info</th>
        <th class="text-center">Join Status</th>
        <th class="text-center">Payment Status</th>
        <th class="text-center">HRDF</th>
      </tr>
    </thead>
    <tbody>

        @foreach ($trainee as $key=>$trainee)
         <tr class="">
        <td class="text-center">{{$key+1}}</td>
        <td>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
            <a href="{{url('trneeattendance/'.encrypt($trainee->tnee_trainPK))}}"   class="dropdown-item btn btn-block btn-primary" type="button">
     <!-- <img src="..." alt="..."> -->
         <i class="fa fa-calendar" aria-hidden="true"></i> Attendance
            </a>
                <button class="dropdown-item btn btn-block btn-success" type="button" data-toggle="modal" data-target="#addTrainee" 
                    data-pk="{{$trainee->trneePK}}"
                    data-pk2="{{$trainee->tnee_trainPK}}"
                    data-invpk="{{$trainee->invPK}}"
                    data-name="{{$trainee->trneeName}}"
                    data-ic="{{$trainee->trneeIcNo}}"
                    data-cmpny="{{$trainee->trneeCmpny}}"
                    data-email="{{$trainee->trneeEmail}}"
                    data-phno="{{$trainee->trneePhNo}}"
                    data-addr="{{$trainee->trneeAddr}}"
                    data-post="{{$trainee->trneePost}}"
                    data-diet="{{$trainee->trneeDiet}}"
                    data-refer="{{$trainee->referrer}}"
                    data-discnt="{{$trainee->invPromocode}}">
            <i class="fa fa-edit" aria-hidden="true"></i> Edit</button>

                <button class="dropdown-item btn btn-block btn-info" type="button" data-toggle="modal" data-target="#changeTraining" 
                    data-pk="{{$trainee->trneePK}}"
                    data-pk2="{{$trainee->tnee_trainPK}}"
                    data-invpk="{{$trainee->invPK}}"
                    data-name="{{$trainee->trneeName}}"
                    >
            <i class="fa fa-share" aria-hidden="true"></i> Change To</button>
            
    @if(!is_null($trainee->invPK))
            <a href="{{url('profoma/'.encrypt($train->trainPK).'/'.encrypt($trainee->trneePK)) }}"   class="dropdown-item btn btn-block btn-primary" type="button">
     <!-- <img src="..." alt="..."> -->
         <i class="fa fa-list" aria-hidden="true"></i> Profoma Invoice
            </a>
            
    
    <a href="#" onclick="var email = prompt('Please enter email you want to send:', '{{$trainee->trneeEmail}}');window.location.replace(`{{url('resendinvoice/'.encrypt($train->trainPK).'/'.encrypt($trainee->trneePK).'/`+email+`') }}`)"   class="dropdown-item btn btn-block  btn-warning" type="button">
     <!-- <img src="..." alt="..."> -->
         <i class="fa fa-envelope" aria-hidden="true"></i> Resend Profoma
    </a>


    @if(is_null($trainee->realinvoicePK))

    <button class="dropdown-item btn btn-block btn-info" type="button" data-toggle="modal" data-target="#generateInvoice" 
                    data-trneepk="{{$trainee->trneePK}}"
                    data-tneetrainpk="{{$trainee->tnee_trainPK}}"
                    >
                    <i class="fa fa-file-text "></i> Generate Invoice</button>
    @else
    <a href="{{url('getinvoice/'.encrypt($train->trainPK).'/'.encrypt($trainee->trneePK)) }}"   class="dropdown-item btn btn-block btn-primary" type="button">
     <!-- <img src="..." alt="..."> -->
         <i class="fa fa-file-text" aria-hidden="true"></i> Get Invoice
            </a>
    @if(is_null($trainee->recptPK))
    <button class="dropdown-item btn btn-block btn-info" type="button" data-toggle="modal" data-target="#generateReciept" 
                    data-trneepk="{{$trainee->trneePK}}"
                    data-tneetrainpk="{{$trainee->tnee_trainPK}}"
                    data-invpk="{{$trainee->realinvoicePK}}"
                    >
    <i class="fa fa-file-text "></i> Generate Reciept</button>
    @else
    <a href="{{url('getreciept/'.encrypt($trainee->recptPK)) }}"   class="dropdown-item btn btn-block btn-primary" type="button">
     <!-- <img src="..." alt="..."> -->
         <i class="fa fa-file-text" aria-hidden="true"></i> Get Reciept
            </a>
            
    @endif
    @endif
    
    @endif
    @if(count($attch) > 0)
    <a href="#" onclick="var email = prompt('Please enter email you want to send:', '{{$trainee->trneeEmail}}');window.location.replace(`{{url('sendhrdf/'.encrypt($train->trainPK).'/'.encrypt($trainee->trneePK).'/`+email+`') }}`)"   class="dropdown-item btn btn-block  btn-warning" type="button">
     <!-- <img src="..." alt="..."> -->
         <i class="fa fa-envelope" aria-hidden="true"></i> Send HRDF
    </a>
    @endif

    

    <form action="{{ url('deletetrainee') }}" method="POST" class="dropdown-item btn-block delete ">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <input type="hidden" name="pk" value="{{$trainee->trneePK}}" />
                            <input type="hidden" name="pk2" value="{{$trainee->tnee_trainPK}}"  />
                            <input type="hidden" name="invPK" value="{{$trainee->invPK}}"  />

                            <button  class="btn btn-block btn-danger"  type="submit"><i class="fa fa-trash "></i> Delete</button>
                        </form>
            </div>
            </div>
       
        </td>
        <td class="text-left">
        <div class="flex-column align-items-start">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">{{$trainee->trneeName}}</h5>
      <small>{{$trainee->trneeIcNo}}</small><br/>
      <strong>{{$trainee->trneePost}}</strong>
    </div>
  </div>

        
        </td>
        <td class="text-left">
            <div class="flex-column align-items-start">
                <small>{{$trainee->trneeCmpny}}</small> <strong></strong>
            </div></td>
            <td class="text-left">
            <div class="flex-column align-items-start">
                <small>Email : </small> <strong>{{$trainee->trneeEmail}}</strong><br/>
                <small>Contact No : </small> <strong>{{$trainee->trneePhNo}}</strong>
            </div></td>
        <td class="text-left">
            <div class="flex-column align-items-start">
                <small>Promo Code : </small> <strong>{{$trainee->invPromocode}}</strong><br/>
                <small>Special Diet : </small> <strong>{{$trainee->trneeDiet}}</strong><br/>
                <small>Referrer : </small> <strong>{{$trainee->referrer}}</strong>
            </div></td>
        <td class="text-center">
        <div class="dropdown">
            <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                
            @switch( $trainee->joinStatus )
                @case('X')
                <i class="fa fa-times text-danger fa-2x"></i>
                @break
                @case('T')
                <i class="fa fa-check text-success fa-2x"></i>
                @break
                @default
                <i class="fa fa-minus text-warning fa-2x"></i>
                @break
            @endswitch
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <form action="{{ url('traineepending') }}" method="POST" class="dropdown-item btn-block ">
                    {{ csrf_field() }}
                    <input type="hidden" name="pk2" value="{{$trainee->tnee_trainPK}}"  />
                    <button  class="btn btn-block btn-warning"  type="submit"><i class="fa fa-minus "></i> Pending</button>
                </form>
                <form action="{{ url('traineeconfirm') }}" method="POST" class="dropdown-item btn-block ">
                    {{ csrf_field() }}
                    <input type="hidden" name="pk2" value="{{$trainee->tnee_trainPK}}"  />
                    <button  class="btn btn-block btn-success"  type="submit"><i class="fa fa-check "></i> Confirm</button>
                </form>
                <form action="{{ url('traineecancel') }}" method="POST" class="dropdown-item btn-block ">
                    {{ csrf_field() }}
                    <input type="hidden" name="pk2" value="{{$trainee->tnee_trainPK}}"  />
                    <button  class="btn btn-block btn-danger"  type="submit"><i class="fa fa-times "></i> Cancel</button>
                </form>
            </div>
        </div>
        
        </td>
        <td class="text-center">
        <div class="dropdown">
            <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                
            @switch( $trainee->paymentStatus )
                @case('F')
                <i class="fa fa-clock-o text-info fa-2x"></i>
                @break
                @case('T')
                <i class="fa fa-check text-success fa-2x"></i>
                @break
                @default
                <i class="fa fa-minus text-warning fa-2x"></i>
                @break
            @endswitch
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <form action="{{ url('paypending') }}" method="POST" class="dropdown-item btn-block ">
                    {{ csrf_field() }}
                    <input type="hidden" name="pk2" value="{{$trainee->tnee_trainPK}}"  />
                    <button  class="btn btn-block btn-warning"  type="submit"><i class="fa fa-minus "></i> Pending</button>
                </form>
                <form action="{{ url('payconfirm') }}" method="POST" class="dropdown-item btn-block ">
                    {{ csrf_field() }}
                    <input type="hidden" name="pk2" value="{{$trainee->tnee_trainPK}}"  />
                    <button  class="btn btn-block btn-success"  type="submit"><i class="fa fa-check "></i> Pay</button>
                </form>
                <form action="{{ url('payfloat') }}" method="POST" class="dropdown-item btn-block ">
                    {{ csrf_field() }}
                    <input type="hidden" name="pk2" value="{{$trainee->tnee_trainPK}}"  />
                    <button  class="btn btn-block btn-info"  type="submit"><i class="fa fa-clock-o "></i> Float</button>
                </form>
            </div>
        </div>
        </td>
        @if($trainee->isHRDF)
        <td class="text-center"><i class="fa fa-check text-success fa-2x"></i></td>
       @else
        <td class="text-center"><i class="fa fa-times text-danger fa-2x"></i></td>

       @endif
        
      </tr>
        @endforeach
    </tbody>
  </table>
     
        
    </div>
</div>
     @endif
</div>

    <div class="modal fade " id="addTrainee" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Trainee Form</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                
            <div class="col-lg-12">  
                
                        
            <form action="{{url('traineeadd')}}" method="POST">
                {{ csrf_field() }}
                
                      <input type="hidden" name="trainFK" value="{{$train->trainPK}}">
                      <input type="hidden" name="pk"/>
                      <input type="hidden" name="pk2"/>
                      <input type="hidden" name="invPK"/>
                <div class="col-sm-6">
            <div class="form-group"><h4>
                <label>Name</label>
                <input class="form-control" name="trneeName" type="text" placeholder="Ahmad bin Abdul" required></h4>
                
                    </div> </div>
                <div class="col-sm-6">
                    <div class="form-group"><h4>
                <label>Company</label>
                <input class="form-control" name="trneeCmpny" type="text" placeholder="Halal Indah sdn bhd" required></h4>
                
            </div>
                </div>
            <div class="col-sm-6">
            <div class="col-sm-12">
		<div class="form-group"><h4>
                <label>Position</label><br>
                 <input id="trneePost" name="trneePost" class="form-control" type="text" placeholder="" required></h4></div>
	</div>
            <div class="col-sm-6">
		<div class="form-group"><h4>
                <label>Ic No</label><br>
                 <input id="trneeIcNo" name="trneeIcNo" class="form-control" type="text" placeholder="" required></h4></div>
	</div>
            <div class="col-sm-6">
		<div class="form-group"><h4>
                <label>Phone No</label><br>
           <input id="trneePhNo" name="trneePhNo" class="form-control" type="tel" placeholder="0123456789" required></h4></div>
                
                </div>
                <div class="col-sm-12">
		<div class="form-group"><h4>
                <label>Email</label><br>
           <input id="trneeEmail" name="trneeEmail" class="form-control" type="email" placeholder="abc@holisticslab.my" required></h4></div>
                
                </div>
               
                <div class="col-sm-6">
		<div class="form-group"><h4>
                <label>Referrer</label><br>
           <input id="referrer" name="referrer" class="form-control" type="Text" placeholder="referrer"></h4></div>
                
                </div>
                <div class="col-sm-6">
		<div class="form-group"><h4>
                <label>Promo Code</label><br>
                 <input id="discntcode" name="discntcode" class="form-control" type="text" placeholder=""></h4></div>
	</div>
                </div>
                 <div class="col-sm-6">
                        <div class="form-group">
                            <h4>
                                <label>Address</label>
                                <textarea class="form-control"  rows="4"  name="trneeAddr" placeholder="Eg: Required company registration" required></textarea>
                            </h4>
                        </div>
                        <div class="form-group"><h4>
                <label>Special Dietary Needs/ Allergies</label><br></h4>
           <textarea class="form-control"  rows="4"  name="trneeDiet" placeholder="Eg: No seafood" ></textarea>
           </div>
                    </div>
                 
                    <button class="button button--large" type="submit">Submit</button>
            </form>
            </div>
                    </div>
</div>
            </div>
        </div>
    </div>


    <div class="modal fade " id="changeTraining" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Change Form</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                
            <div class="col-lg-12">  
                
                        
            <form action="{{url('traineechange')}}" method="POST">
                {{ csrf_field() }}
                
                      <input type="hidden" name="trainFK" value="{{$train->trainPK}}">
                      <input type="hidden" name="pk"/>
                      <input type="hidden" name="pk2"/>
                      <input type="hidden" name="invPK"/>

                    <div class="col">
                        <div class="form-group">
                            <h4>
                                <label >Change</label>
                                
                            </h4>
                            <label id="trneeName"></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <h4>
                                <label >from</label>
                                
                            </h4>
                                <label id="trainfrom" >{{$train->trainName}} ( {{ $train->trainDtStart.' - '.$train->trainDtEnd}})</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <h4>
                                <label for="sel1">To</label>
                                
                            </h4>
                            <select class="form-control custom-select" id="sel1" name="newtrainPK" Required>
                                @foreach ($trainlist as $key=>$trainlist)
                                    <option value="{{$trainlist->trainPK}}" >{{$trainlist->trainName}} ( {{ $trainlist->trainDtStart.' - '.$trainlist->trainDtEnd}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
               
                 
                    <button class="button button--large" type="submit">Submit</button>
            </form>
            </div>
                    </div>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="generateInvoice" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Generate Invoice</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                
            <div class="col-lg-12">  
                
                        
            <form action="{{url('invoice')}}" method="POST">
            {{ csrf_field() }}
                    <input type="hidden" name="trainPK" value="{{$train->trainPK}}">
                    <input type="hidden" name="trneePK" value="" />
                    <input type="hidden" name="tnee_trainPK" value=""  />

                    <div class="col">
                        <div class="form-group"><h4>
                            <label>Invoice Number</label><br>
                            <input id="invNo" name="invNo" class="form-control" type="text" placeholder="HL/INV201907/00001" required></h4>
                        </div>
                    
                    </div>
                    <div class="col">
                        <div class="form-group">
                        <h4>
                            <label>Invoice Date</label><br>
                            <input id="invDate" name="invDate" class="form-control" type="date" placeholder="" required></h4></div>
                    
                        </div>
                    </div>
                   
               
                 
                    <button class="button button--large" type="submit">Submit</button>
            </form>
            </div>
                    </div>
            </div>
            </div>
        </div>
    </div>


    <div class="modal fade " id="generateReciept" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Generate Invoice</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                
            <div class="col-lg-12">  
                
                        
            <form action="{{url('reciept')}}" method="POST">
            {{ csrf_field() }}
                    <input type="hidden" name="trainPK" value="{{$train->trainPK}}">
                    <input type="hidden" name="trneePK" value="" />
                    <input type="hidden" name="tnee_trainPK" value=""  />
                    <input type="hidden" name="invPK" value=""  />

                    <div class="col">
                        <div class="form-group"><h4>
                            <label>Reciept Number</label><br>
                            <input id="invNo" name="invNo" class="form-control" type="text" placeholder="HL/INV201907/00001" required></h4>
                        </div>
                    
                    </div>
                    <div class="col">
                        <div class="form-group">
                        <h4>
                            <label>Reciept Date</label><br>
                            <input id="invDate" name="invDate" class="form-control" type="date" placeholder="" required></h4>
                        </div>
                    
                        </div>
                    <div class="col">
                        <div class="form-group">
                        <h4>
                            <label>Payment By</label><br>
                            <select class="form-control custom-select" id="sel1" name="paymentType" Required>
                                    <option value="CASH" >Cash</option>
                                    <option value="BANK TRFR" >Bank Transfer</option>
                                    <option value="CHEQUE" >Cheque</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                        <h4>
                            <label>Check Number </label><br>
                            <input id="chqNo" name="chqNo" class="form-control" type="text" placeholder="120938 12901 1921 12"></h4>
                        </div>
                    </div>
               
                 
                    <button class="button button--large" type="submit">Submit</button>
            </form>
            </div>
                    </div>
            </div>
            </div>
        </div>
    </div>

<div class="modal fade " id="traningPunch" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Punch</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                
            <div class="col-lg-12">  
                <form class="form-horizontal" action="{{url('traineepunch')}}" id="punch" method="get">
                      <input type="hidden" name="trainFK" value="{{$train->trainPK}}">
						<div class="form-group">
							<label for="name" class="cols-sm-2 control-label">IC No</label>
							<div class="cols-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
									<input type="text" class="form-control" name="icno" id="icno"  placeholder="ic without '-'"/>
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

<div class="modal fade " id="addDiscount" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Discount</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                                
            <div class="col-lg-12">  
                <form class="form-horizontal" action="{{url('adddiscount')}}" id="discntForm" method="post">
                {{ csrf_field() }}
                      <input type="hidden" name="trainFK" value="{{$train->trainPK}}">
						
                        <div class="col-sm-4">
		<div class="form-group"><h4>
                <label>Discount %</label><br>
           <input id="discntval" name="discntval" step="any" class="form-control" type="number" placeholder="10" required></h4></div>
                
                </div>
                
                <div class="col-sm-4">
		<div class="form-group"><h4>
                <label>Discount Code</label><br>
           <input id="discntcode" name="discntcode" class="form-control" type="text" placeholder="10" required></h4></div>
                
                </div>
                <div class="col-sm-4">
		<div class="form-group"><h4>
                <label>Discount is Auto</label><br>
                <input checked="checked" id="discntauto" name="discntauto" class="form-control"  type="checkbox" value="yes">   
                </div>
                <div class="col-sm-4">
		<div class="form-group"><h4>
                <label>Start Date</label><br>
                 <input id="discntfrom" name="discntfrom" class="form-control" type="date" placeholder="" required></h4></div>
	</div>
    <div class="col-sm-4">
		<div class="form-group"><h4>
                <label>End Date</label><br>
                 <input id="discntto" name="discntto" class="form-control" type="date" placeholder="" required></h4></div>
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
$('#addTrainee').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
   let pk = button.data('pk') // Extract info from data-* attributes
   let pk2 = button.data('pk2') // Extract info from data-* attributes
   let invpk = button.data('invpk') // Extract info from data-* attributes
   let name = button.data('name') 
   let ic = button.data('ic') 
   let cmpny = button.data('cmpny') 
   let email = button.data('email') 
   let phno = button.data('phno') 
   let addr = button.data('addr') 
   let post = button.data('post') 
   let diet = button.data('diet') 
   let refer = button.data('refer') 
   let discnt = button.data('discnt') 


//   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
//   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('[name="pk"]').val(pk)
  modal.find('[name="pk2"]').val(pk2)
  modal.find('[name="invPK"]').val(invpk)
  modal.find('[name="trneeName"]').val(name)
  modal.find('[name="trneeIcNo"]').val(ic)
  modal.find('[name="trneeCmpny"]').val(cmpny)
  modal.find('[name="trneeEmail"]').val(email)
  modal.find('[name="trneePhNo"]').val(phno)
  modal.find('[name="trneeAddr"]').val(addr)
  modal.find('[name="trneePost"]').val(post)
  modal.find('[name="referrer"]').val(refer)
  modal.find('[name="trneeDiet"]').val(diet)
  modal.find('[name="discntcode"]').val(discnt)

})

$('#changeTraining').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
   let pk = button.data('pk') // Extract info from data-* attributes
   let pk2 = button.data('pk2') // Extract info from data-* attributes
   let invpk = button.data('invpk') // Extract info from data-* attributes
   let name = button.data('name') 
  

//   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
//   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('[name="pk"]').val(pk)
  modal.find('[name="pk2"]').val(pk2)
  modal.find('[name="invPK"]').val(invpk)
  modal.find('[id="trneeName"]').text(name)
})

$('#generateInvoice').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
   let trneePK = button.data('trneepk') // Extract info from data-* attributes
   let tnee_trainPK = button.data('tneetrainpk') // Extract info from data-* attributes
  

//   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
//   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('[name="trneePK"]').val(trneePK)
  modal.find('[name="tnee_trainPK"]').val(tnee_trainPK)
})

$('#generateReciept').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
   let trneePK = button.data('trneepk') // Extract info from data-* attributes
   let tnee_trainPK = button.data('tneetrainpk') // Extract info from data-* attributes
   let invPK = button.data('invpk') // Extract info from data-* attributes
  

//   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
//   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('[name="trneePK"]').val(trneePK)
  modal.find('[name="tnee_trainPK"]').val(tnee_trainPK)
  modal.find('[name="invPK"]').val(invPK)
})


$(".delete").on("submit", function(){
        return confirm("Are you sure to delete this item?");
    });

</script>
@endsection