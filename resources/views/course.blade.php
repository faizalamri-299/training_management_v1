@extends('layouts.mainmaster')
@section('page_heading','Desktop')
@section('section')

   

 <div class="col-sm-10 col-sm-offset-1">
     <div class="panel panel-primary panelbutton">
		<div class="panel-heading" style="padding:0;">
			<p class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" class="btn btn-lg collapsed btn-block">
			<i class="fa fa-plus-circle" aria-hidden="true"></i>
	Add Course
			</a>
		</p>
	</div>
	<div id="collapse1" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
		<div class="panel-body">
            
                   
            <form action="{{url('addCourse')}}" method="POST">
                {{ csrf_field() }}
                <div class="col-sm-6">
            <div class="form-group"><h4>
                <label>Course Name</label>
                <input class="form-control" name="courseName" type="text" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> </div>
               
                <div class="col-sm-3">
            <div class="form-group"><h4>
                <label>Course Fee</label>
                <input class="form-control" name="courseFee" type="text" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> </div>
                    <div class="col-sm-3">
            <div class="form-group"><h4>
                <label>Course Link</label>
                <input class="form-control" name="courseLink" type="text" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> </div>
                    
                 <div class="col-sm-12">
                        <div class="form-group">
                            <h4>
                                <label>Course Desc</label>
                                <textarea id="summernote" class="form-control"  rows="4"  name="courseDesc" placeholder="Eg: Required company registration" required></textarea>

                            </h4>
                        </div>
                    </div>
                 
                    <button class="button button--large" type="submit">Submit</button>
            </form>
		</div>
	</div>
</div>
     
         @if (count($course) > 0)
  <table class="table table-striped">
    <thead>
      <tr class="info">
        <th class="text-center">No</th>
        <th class="text-center">Course Name</th>
        <th class="text-center">Course Desc</th>
        <th class="text-center">Course Fee</th>
        <th></th>
      </tr>
    </thead>
    <tbody>

        @foreach ($course as $key=>$course)
         <tr class="">
        <td class="text-center">{{$key+1}}</td>
        <td class="text-center"><a href="{{url('course/'.encrypt($course->coursePK)) }}">{{$course->courseName}}</a></td>
        <td >{!! $course->courseDesc!!}</td>
        <td class="text-center">{{$course->courseFee}}</td>
        <td>
            <button type="button" data-toggle="modal"
                data-target="#edtCourse" data-courseid="{{$course->coursePK}}"
                data-coursename="{{$course->courseName}}"
                data-coursedesc="{{$course->courseDesc}}"
                data-coursefee="{{$course->courseFee}}"
                data-courselink="{{$course->courseLink}}"
                
                class="btn btn-warning btn-circle btn-sm">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </button>
        </td>
      </tr>
        @endforeach
    </tbody>
  </table>
     
        @endif
</div>

<div class="modal fade qs-item-modal-lg" id="edtCourse" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myLargeModalLabel">Edit Course</h4>
                </div>
                
            <form action="{{url('updCourse')}}" method="POST">
                {{ csrf_field() }}
                
                <input type="hidden" name="courseid" value="">
                <div class="col-sm-6">
            <div class="form-group"><h4>
                <label>Course Name</label>
                <input class="form-control" name="courseName" type="text" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> </div>
               
                    <div class="col-sm-3">
            <div class="form-group"><h4>
                <label>Course Fee</label>
                <input class="form-control" name="courseFee" type="text" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> </div>
                    <div class="col-sm-3">
            <div class="form-group"><h4>
                <label>Course Link</label>
                <input class="form-control" name="courseLink" type="text" placeholder="Halal Executive Jakim" required></h4>
                
                    </div> </div>
              
                 <div class="col-sm-12">
                        <div class="form-group">
                            <h4>
                                <label>Course Desc</label>
                                <textarea id="courseDesc" class="form-control"  rows="4"  name="courseDesc" placeholder="Eg: Required company registration" required></textarea>

                            </h4>
                        </div>
                    </div>
                 
                    <button class="button button--large" type="submit">Submit</button>
            </form>
            </div>
        </div>
    </div>
@stop