@extends('layouts.mainmaster')
@section('page_heading',$course->courseName)
@section('section')

   
 <div class="col-sm-10 col-sm-offset-1">
  <div class="panel panel-default">
  <div class="panel-heading"><strong>Course Attachment</strong></div>
  <div class="panel-body">
     <div class="form-group">
        
            <div class="col-sm-12">
                <div class="text-left">
                    <h5>
                        <label>Course Name</label>
                    </h5>
                    <p>{{strtoupper($course->courseName)}}</p>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="text-left">
                    <h5>
                        <label>Training Place</label>
                    </h5>
                    <p>{!!$course->courseDesc!!}</p>
                </div>
            </div>
           
   
    </div>
    </div>
</div>
<div class="col-sm-10 col-sm-offset-1">
     <div class="panel panel-primary panelbutton">
		<div class="panel-heading" style="padding:0;">
			<p class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" class="btn btn-lg collapsed btn-block">
			<i class="fa fa-plus-circle" aria-hidden="true"></i>
	Add Attachment
			</a>
		</p>
	</div>
	<div id="collapse1" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
		<div class="panel-body">
            
                   
            <form action="{{url('addattchment')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="courseFK" value="{{$course->coursePK}}">
                <div class="col-sm-6">
                    <div class="form-group">
                        <h4>
                            <label>Description</label>
                            <input class="form-control" name="attchdesc" type="text" placeholder="Halal Executive Jakim" required>
                        </h4>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <h4>
                            <label>Attachment Type</label>
                            <select class="form-control custom-select" id="sel1" name="attchtype" Required>
                                    <option value="HRDF" >HRDF Document</option>
                                    <option value="EMAIL" >Email</option>
                            </select>
                        </h4>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <h4><label>Attachment File</label></h4>
                        <div class="col-sm-12">
                        <div id="uploadFile" class="input-group mb-3 row">
                            <label id="filename"  class=" text-md-right"></label>
                        </div>
                        <div id="uploadDiv" class="input-group mb-3 row">
                            <div class="custom-file">
                                    <input type="file" name="upload" id="upload"  onchange="gettitle(this)"  class="custom-file-input" accept="image/*,application/pdf" >
                                    <label class="custom-file-label" for="upload">Choose file</label>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
               
                    <button class="button button--large" type="submit">Submit</button>
                    
            </div>
            </form>
		</div>
	</div>
</div>
     
        @if (count($attch) > 0)
  <div class="panel panel-default">
  <div class="panel-body">
  <table class="table table-striped">
    <thead>
      <tr class="info">
        <th class="text-center">No</th>
        <th class="text-center">Description</th>
        <th class="text-center">Directory</th>
        <th class="text-center">Type</th>
        <th class="text-center"></th>
      </tr>
    </thead>
    <tbody>

        @foreach ($attch as $key=>$attch)
         <tr class="">
        <td class="text-center">{{$key+1}}</td>
        <td class="text-center">{{$attch->attchDesc}}</td>
        <td class="text-center">{{$attch->attchLink}}</td>
        <td class="text-center">{{$attch->attchType}}</td>
        <td>
        <form action="{{ url('deleteattch') }}" method="POST" class="dropdown-item btn-block delete ">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <input type="hidden" name="pk" value="{{$attch->attchPK}}" />
                            <button  class="btn btn-block btn-danger"  type="submit"><i class="fa fa-times "></i> Delete</button>
                        </form>
        </td>
      </tr>
        @endforeach
    </tbody>
  </table>
     
        
    </div>
</div>
     @endif


     
 <div class="col-sm-12">
     <div class="panel panel-primary panelbutton">
		<div class="panel-heading" style="padding:0;">
			<p class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse_cert" aria-expanded="false" class="btn btn-lg collapsed btn-block">
			<i class="fa fa-plus-circle" aria-hidden="true"></i>
	Add Certificate
			</a>
		</p>
	</div>
	<div id="collapse_cert" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
		<div class="panel-body">
            
                   
            <form action="{{url('addcert')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="courseFK" value="{{$course->coursePK}}">
                <div class="col-sm-8">
            <div class="form-group"><h4>
                <label>Cert Name</label>
                <input class="form-control" name="certName" type="text" placeholder="Sijil Penyertaan" required></h4>
                
                    </div> </div>
               
                
                    
                    <div class="col-sm-3">
                    <div class="form-group">
                        <h4><label>Template File</label></h4>
                        <div class="col-sm-12">
                        <div id="uploadFile" class="input-group mb-3 row">
                            <label id="filename"  class=" text-md-right"></label>
                        </div>
                        <div id="uploadDiv" class="input-group mb-3 row">
                            <div class="custom-file">
                                    <input type="file" name="upload" id="upload"  onchange="gettitle(this)"  class="custom-file-input" accept="image/*" >
                                    <label class="custom-file-label" for="upload">Choose file</label>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                 
                    <button class="button button--large" type="submit">Submit</button>
            </form>
		</div>
	</div>
</div>

@if (count($certs) > 0)
  <div class="panel panel-default">
  <div class="panel-body">
  <table class="table table-striped">
    <thead>
      <tr class="info">
        <th class="text-center">No</th>
        <th class="text-center">Name</th>
        <th class="text-center">Directory</th>
        <th class="text-center"></th>
      </tr>
    </thead>
    <tbody>

        @foreach ($certs as $key=>$cert)
         <tr class="">
        <td class="text-center">{{$key+1}}</td>
        <td class="text-center">{{$cert->certName}}</td>
        <td class="text-center">
        <img src="{{ url('getfile/'.encrypt($cert->certLink)) }}" class="img-responsive img-preview">
        </td>
        
                 
        <td>
        <form action="{{ url('deletecert') }}" method="POST" class="dropdown-item btn-block delete ">
                            {{ csrf_field() }} {{ method_field('DELETE') }}
                            <input type="hidden" name="pk" value="{{$cert->certPK}}" />
                            <button  class="btn btn-block btn-danger"  type="submit"><i class="fa fa-times "></i> Delete</button>
                        </form>
        </td>
      </tr>
        @endforeach
    </tbody>
  </table>
     
        
    </div>
</div>
     @endif
</div>


@endsection

@section('page-script')
<script>

function gettitle(input) {
//     var fReader = new FileReader();
// fReader.readAsDataURL(input.files[0]);
// fReader.onloadend = function(e){
//     $('#img').attr('src', e.target.result);
// }

var url = input.value;
var filename = url.substring(url.lastIndexOf('\\') + 1).toLowerCase();

if (input.files){
    var reader = new FileReader();

    reader.onload = function (e) {
        showFile(filename)
    }
    reader.readAsDataURL(input.files[0]);
}
else{
    $('#filename').text('')
  }
}

function showFile(src){
    
   // $('#uploadDiv').hide();
   console.log(src)
    $('#filename').text(src);
    $('#uploadFIle').show();
    

}


</script>
@endsection