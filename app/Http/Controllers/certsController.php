<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class certsController extends Controller
{
    //

  
    public function getList($ic)
    {

        $user=  \App\Models\trainees::where('trneeIcNo',$ic)->select('trneeName','trneeIcNo','trneePK')->first();
        $post =  \App\Models\trainings::leftJoin('trainee_trainings', 'trainPK','=', 'trainee_trainings.trainFK')
        ->leftJoin('courses', 'courseFK','=', 'courses.coursePK')
        ->leftJoin('trainees', 'trainees.trneePK','=', 'trainee_trainings.trneeFK')
        ->join('certificates', 'certificates.courseFK','=', 'courses.coursePK')
        ->where('trainees.trneeIcNo',$ic)
        ->where('paymentStatus','T')
        ->select('courseName','courseDesc','trainDtEnd','trainDtStart','trainName','certLink','certName')
        ->get();

        foreach($post as $a)
        {
                $a->certLink=url('hpb.getfile/'.encrypt($a->certLink));
        }

        $user->certs=$post;
        return response()->json($user);
        
    }

    function getfile($dir){
        $dir=decrypt($dir);
        $file = Storage::get($dir);
        
        if (!$file) {
            abort(404);
        }
        $type =  Storage::mimeType($dir);
    
        return response()->make($file, 200)->header("Content-Type", $type);
    }

    
}
