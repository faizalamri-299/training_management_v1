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
        <th>Remarks</th>
      </tr>
    </thead>
    <tbody>
         @if (count($attnd) > 0)
                                        
                                            @foreach ($attnd as $key=>$attnd)
         <tr>
        <td class="text-center">{{$key+1}}</td>
        <td>{{strtoupper($attnd->empname)}}</td>
        <td class="text-center">{{$attnd->in}}</td>
        <td class="text-center">{{$attnd->out}}</td>
        <td></td>
      </tr>
                                        
                                        @endforeach
                                        @endif
     
      
    </tbody>
  </table>
                </div>
     
     
</div>

@stop