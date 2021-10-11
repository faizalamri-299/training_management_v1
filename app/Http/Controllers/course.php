<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use View;
use Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

use Mail;
class course extends Controller

	{
	function add(Request $request)
		{
      //  $response = (object)['status' => '', 'message' => '' ];
        

        $crs = new \App\Models\courses;
        $crs->courseName=$request->courseName;
        $crs->courseFee=$request->courseFee;
        $crs->courseLink=$request->courseLink;
        $crs->courseDesc=$request->courseDesc;
        $crs->save();
            
  
         
         //   $response->status ="PASS";
         //   $response->message ="New employee has succesfully added";
       // return response(($birthdate."<br>".$gender), 200);
        
      
        
       // return response()->json($response);
        
        return redirect()->back();
    }
    
    function update(Request $request)
		{
      //  $response = (object)['status' => '', 'message' => '' ];
        

        $crs = \App\Models\courses::where('coursePK',$request->courseid)->firstOrFail();
        $crs->courseName=$request->courseName;
        $crs->courseFee=$request->courseFee;
        $crs->courseDesc=$request->courseDesc;
        $crs->courseLink=$request->courseLink;
        $crs->save();
            
  
         
         //   $response->status ="PASS";
         //   $response->message ="New employee has succesfully added";
       // return response(($birthdate."<br>".$gender), 200);
        
      
        
       // return response()->json($response);
        
        return redirect()->back();
    }
    function get(){
         $crs= \App\Models\courses::all();
          return  response()->json($crs)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
    function getCourseName($code){
        $crs = \App\Models\courses::select('courseName','courseLink')->where('courseLink',$code)->firstOrFail();
        return  response()->json($crs)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    
    }
    	function getdtl($id)
		{
            $id=decrypt($id);
            
        $course = \App\Models\courses::where('coursePK', $id)->firstOrFail();
            
        $attch = \App\Models\attachments::where('courseFK', $id)->get();
        $cert = \App\Models\certificate::where('courseFK', $id)->get();
            
           // dd($train->trainName);
            
            
      return view('courseAttch', ['course' => $course ,'attch' => $attch ,'certs' => $cert ]);
        
    }
    
    function addtrainee(Request $request)
		{
            
         $train = new \App\Models\trainees;
        $train->trneeIcNo=$request->trneeIcNo;
        $train->trneeName=$request->trneeName;
        $train->trneeAddr=$request->trneeAddr;
        $train->trneeCmpny=$request->trneeCmpny;
        $train->trneeEmail=$request->trneeEmail;
        $train->trneePhNo=$request->trneePhNo;
        $train->save();
            
  
        
         $train2 = new \App\Models\trainee_trainings;
        $train2->trneeFK=$train->trneePK;
        $train2->trainFK=$request->trainFK;
        
        $train2->save();
            
        
        return redirect()->back();
        
    }

    function addattch(Request $request)
		{
      $attch = new \App\Models\attachments;
      $attch->attchDesc=$request->attchdesc;
      $attch->attchType=$request->attchtype;
      $attch->courseFK=$request->courseFK;

      if ($request->hasFile('upload')) {
        $path = $request->upload->store($request->courseFK.'/'.$request->attchtype);
        $attch->attchLink=$path;
        }
      
     $attch->save();

      return redirect()->back();
    }

    function deleteattch(Request $request){
      $attch=\App\Models\attachments::where('attchPK',$request->pk);
      $attchfile=$attch->first();
      Storage::delete($attchfile->attchLink);
      $attch->delete();
      return redirect()->back();
    }


    function addcert(Request $request)
		{
      $cert = new \App\Models\certificate;
      $cert->certName=$request->certName;
      $cert->courseFK=$request->courseFK;

      if ($request->hasFile('upload')) {
        $path = $request->upload->store($request->courseFK);
        $cert->certLink=$path;
        $cert->save();
        
        }
        
      

      return redirect()->back();
    }

    function deletecert(Request $request){
      $cert=\App\Models\certificate::where('certPK',$request->pk);
      $certfile=$cert->first();
      Storage::delete($certfile->certLink);
      $cert->delete();
      return redirect()->back();
    }
    
    function sendhrdf($id,$userid,$email=null){

        
      $id=decrypt($id);
      $userid=decrypt($userid);
      $training=\App\Models\trainings::leftJoin('courses', 'courses.coursePK','=', 'courseFK')->where('trainPK',$id)->first();
      $trainee=\App\Models\trainees::where('trneePK',$userid)->first();

      $attch = \App\Models\attachments::where('courseFK', $training->coursePK)->where('attchType','HRDF')->get();
    

     $emailto=strtolower($trainee->trneeEmail);
      $data = [];
      
      Mail::send('hrdfmail', $data, function($message)use ($trainee,$email,$emailto,$attch) {

          $message->to($email === null ? $emailto : $email, $trainee->trneeName)->subject
              ("Send HRDF Document");
          $message->from('training@holisticslab.my','Holisticslab Training Department');

          for ($i=0; $i < count($attch); $i++) {
            $contents = Storage::get($attch[$i]->attchLink);
            $ext = pathinfo($attch[$i]->attchLink, PATHINFO_EXTENSION);
            $message->attachData($contents,$attch[$i]->attchDesc.'.'.$ext);
        }
      });
      
      return redirect()->back();
  }
    
       function punch(Request $request)
		{
             
                 $diff=date_diff(date_create(),date_create());
        $response = (object)['status' => '', 'message' => '' ];
        
        $train = \App\Models\trainee_trainings::leftJoin('trainees', 'trainees.trneePK','=', 'trneeFK')->where('trainFK', $request->trainFK)->where('trneeIcNo', $request->icno)->first();
           
           if(count($train)>0){
               
        $prevattnd =\App\Models\trainingattnds::where('tnee_trainFK', $train->tnee_trainPK)->orderBy('tattndPK', 'DESC')->first();
           
           if($prevattnd){
                     $diff=date_diff(date_create($prevattnd->tattndTimestamp,timezone_open("Asia/Kuala_Lumpur")),date_create());
           }
         if ($diff->format("%h")>0 || $diff->format("%s")==0){
             
            $attnd = new \App\Models\trainingattnds;
        $attnd->tnee_trainFK=$train->tnee_trainPK;
        $attnd->save();
             
              $response->status ="PASS";
            $response->message ="Welcome ".$train->trneeName."\nYou are successfully punch ";
         }
         else {
            $response->status ="FAILED";
             if($diff->format("%i")>0){
                 $response->message ="Hello ".$train->trneeName.",\nYou are  just punch ".$diff->format("%i Minutes")." ago";}
             else{ $response->message ="You already punch in to the system";}
           
         }
               }
           else{
            $response->status ="FAILED";
               $response->message ="user not found";
           }
    
        
          
         
        
        return response()->json($response);
        
    }
    
    function getAttnd(Request $request, $id)
		{
        
        
            $id=decrypt($id);
        
        $train = \App\Models\trainings::where('trainPK', $id)->firstOrFail();
        $data=[];
          $currdate=strtotime(date('Y-m-d'));
        if (isset($request->currdate))
        {
            
            $currdate=strtotime($request->currdate);
            $now = time();
            if ($currdate > $now) {
             $currdate=strtotime(date('Y-m-d'));
            }
        }
        
       
        
        $trainee = \App\Models\trainees::leftJoin('trainee_trainings', 'trneePK','=', 'trainee_trainings.trneeFK')->where('trainFK', $id)->get();
        
        
       
             foreach($trainee as $a)
             {
                 $response = (object)['empname' => '', 'in' => '', 'out' => '', 'icno' => '' ];
                 
                 $in =  \App\Models\trainingattnds::where('tnee_trainFK', $a->tnee_trainPK)->where('tattndTimestamp', '>=', date('Y-m-d',$currdate).' 00:00:00')->where('tattndTimestamp', '<=', date('Y-m-d',strtotime("+1day", $currdate)).' 00:00:00')->orderBy('tattndPK', 'ASC')->first();
                 
                 $out =  \App\Models\trainingattnds::where('tnee_trainFK', $a->tnee_trainPK)->where('tattndTimestamp', '>=', date('Y-m-d',$currdate).' 00:00:00')->where('tattndTimestamp', '<=', date('Y-m-d',strtotime("+1day", $currdate)).' 00:00:00')->orderBy('tattndPK', 'DESC')->first();
                 
                 $response->empname = $a->trneeName;
                 
                 $diff=date_diff(date_create(),date_create());
                 
                 if ($out) {$response->out = date('h:i:s A',strtotime($out->tattndTimestamp));}
                 if ($in) {$response->in = date('h:i:s A',strtotime($in->tattndTimestamp));
                           
                 if($response->in===$response->out ){
                     $response->out ='';
                     $diff=date_diff(date_create($in->tattndTimestamp),date_create());}
                 else{
                     $diff=date_diff(date_create($in->tattndTimestamp),date_create($out->tattndTimestamp));
                 }
                 
                 
                          }
					//$response->in = $in->tattndTimestamp;
					//$response->out = $out->tattndTimestamp;
					$response->icno = $a->trneeIcNo;
                 
                 array_push($data, $response);
             }
        
        return view('attendance', ['train' => $train ,'attnd' => $data,'currdate'=>$currdate]);
    }
   
    function gettrainlist(){
        
        $response = (object)['status' => '', 'message' => '' ];
        
                      $post =  \App\Models\trainings::where('trainDtStart', '<=', DB::raw('DATE(NOW())'))->where('trainDtEnd', '>=',DB::raw('DATE(NOW())'))->get();
        return response()->json($post);
    }
     function gettrainlistall(){
        
        $response = (object)['status' => '', 'message' => '' ];
        
                      $post =  \App\Models\trainings::get();
        return response()->json($post);
    }
      function gettrainattnd(Request $request, $id){
          
        $trainee = \App\Models\trainees::leftJoin('trainee_trainings', 'trneePK','=', 'trainee_trainings.trneeFK')->where('trainFK', $id)->get();
          
          $data=[];
            
          $currdate=strtotime(date('Y-m-d'));
        if (isset($request->currdate))
        {
            
            $currdate=strtotime($request->currdate);
            $now = time();
            if ($currdate > $now) {
             $currdate=strtotime(date('Y-m-d'));
            }
        }
       
             foreach($trainee as $a)
             {
                 $response = (object)['name' => '', 'icno' => '', 'attnd' => '' ];
                 
                 $attnd =  \App\Models\trainingattnds::where('tnee_trainFK', $a->tnee_trainPK)->where('tattndTimestamp', '>=', date('Y-m-d',$currdate).' 00:00:00')->where('tattndTimestamp', '<=', date('Y-m-d',strtotime("+1day", $currdate)).' 00:00:00')->orderBy('tattndPK', 'ASC')->get();
                 
                 $response->name = $a->trneeName;
					$response->icno = $a->trneeIcNo;
					$response->attnd = $attnd;
                 
                 array_push($data, $response);
             }
          
        return response()->json($data);
    }
 
	}

