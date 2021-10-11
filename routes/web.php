<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/config-cache', function() {
    Artisan::call('config:cache');
    return "create cache";
});
Route::get('/storagelink', function() {
    Artisan::call('storage:link');
    return "create link";
});



Route::get('/getimg/{dir?}','training@getimg');
Route::get('/login', function()
{
    // return View::make('login');
    return view('auth/login');
})->name('login');

Route::post('/loginauth','authController@authenticate');

Route::get('/logout', function()
{
    auth()->logout();
    Session::flash('success', 'You have been successfully logged out!');
	return redirect('/login');
});

Route::get('/traintoday', 'training@gettrainlist');
Route::get('/trainall', 'training@gettrainlistall');
Route::get('/trainattnd/{id}', 'training@gettrainattnd');
    
Route::get('/traineepunch', 'training@punch');

Route::group(['middleware' => 'auth'], function () {
Route::get('/', function () {
    
      if (auth()->user()->isSuperAdmin) {
          return view('admin/desktop');
    }
    else{

        return view('desktop');
    }
});

Route::get('/course', function () {
    
    $post =  App\Models\courses::all();
      return view('course', ['course' => $post ]);
});

Route::get('/course/{id?}','course@getdtl' );
Route::post('/addattchment','course@addattch' );
Route::delete('deleteattch','course@deleteattch');

Route::post('/addcert','course@addcert' );
Route::delete('deletecert','course@deletecert');

Route::get('/sendhrdf/{id?}/{userid?}/{email?}','course@sendhrdf');

Route::get('/train','training@gettrainadminlist');
Route::get('/setting', function () {
    
    $invoice = \App\Models\invgenerators::all();
    try
    {
        
    $biller = \App\Models\billers::where('billerPK',1)->firstOrFail();
    }
    // catch(Exception $e) catch any exception
    catch(ModelNotFoundException $e)
    {
        $biller = new \App\Models\billers;
        $biller->billerName="";
        $biller->billerEmail="";
        $biller->billerTel="";
        $biller->billerAddress="";
        $biller->billerSubject="";
        $biller->billerNote="";
        $biller->save();

    }
    $states = \App\Models\states::all();
      return view('setting', ['invoice' => $invoice,'biller'=>$biller,'states'=>$states ]);
});

Route::get('/admin', function () {
    
    $users = \App\Models\User::all();
      return view('admin', ['users' => $users]);
});


Route::post('/updinvoicesetting' , function(Request $request) {
    
    try
    {
        $invoice = \App\Models\invgenerators::where('invYear',$request->year)->firstOrFail();
        $invoice->invLastNo=$request->lastindex;
        $invoice->invCode=$request->prefix;
        $invoice->invType=$request->type;
        
        $invoice->save();
    }
    // catch(Exception $e) catch any exception
    catch(ModelNotFoundException $e)
    {
        $invoice = new \App\Models\invgenerators;
        $invoice->invYear= $request->year;
        $invoice->invLastNo=$request->lastindex;
        $invoice->invCode=$request->prefix;
        
        $invoice->save();

    }
    return redirect()->back();
});

Route::post('/updbiller' , function(Request $request) {
  
        $billers = \App\Models\billers::where('billerPK',1)->firstOrFail();
        $billers->billerName=$request->name;
        $billers->billerEmail=$request->email;
        $billers->billerTel=$request->tel;
        $billers->billerAddress=$request->address;
        $billers->billerSubject=$request->subject;
        $billers->billerNote=$request->note;
        
        $billers->save();
    
    
    return redirect()->back();
});


Route::post('/updpwd' , function(Request $request) {
  
    $User = \App\Models\User::where('userPK',$request->userid)->firstOrFail();
    $User->password=Hash::make($request->newpwd);
    
    $User->save();


return redirect()->back();
});
Route::post('/addadmin' , function(Request $request) {
  
    $User = new \App\Models\User;
    $User->username=$request->username;
    $User->password=Hash::make($request->password);
    
    $User->save();


return redirect()->back();
});

Route::post('/addState' , function(Request $request) {
    
  
        $states = new \App\Models\states;
        $states->stateName= $request->stateName;
        
        $states->save();

    
    return redirect()->back();
});



Route::post('/trainadd',  'training@add' );
Route::post('/trainupdate',  'training@update' );
Route::get('/training/{id?}','training@getdtl' );
Route::post('/invoice','training@createinvoice' );
Route::post('/reciept','training@createreciept' );
Route::get('/getinvoice/{id?}/{userid?}','training@getinvoice' );
Route::get('/getreciept/{id?}','training@getreciept' );
Route::get('/profoma/{id?}/{userid?}','training@getprofomainvoice' );
Route::get('/resendinvoice/{id?}/{userid?}/{email?}','training@resendInvoice' );

Route::post('/traineeadd',  'training@addtrainee' );
Route::post('/traineechange',  'training@changetrainee' );

Route::post('/adddiscount',  'training@adddiscount' );
Route::delete('deletediscount','training@deletediscount');
Route::delete('deletetrainee','training@deletetrainee');
Route::delete('deletetraining','training@deleteTraining');



Route::post('/copytotest',  'training@copytest' );

Route::post('/traineepending',  'training@traineepending' );
Route::post('/traineeconfirm',  'training@traineeconfirm' );
Route::post('/traineecancel',  'training@traineecancel' );


Route::post('/paypending',  'training@paypending' );
Route::post('/payconfirm',  'training@payconfirm' );
Route::post('/payfloat',  'training@payfloat' );



Route::get('/attendance/{id}', 'training@getAttnd');
// Route::get('/trneeattendance/{id}', 'attendanceController@getindattnd');
Route::get('/trneeattendance/{id}/{hrdf?}', 'attendanceController@getindattnd');
Route::get('/attendance_backup/{id}/{ic}', 'training@getindattnd');
Route::get('/attendance/{id}', 'attendanceController@getAttnd');;

Route::post('/addnewattnd', 'attendanceController@addnewattnd');
Route::post('/addindicator', 'attendanceController@addNotes');

Route::delete('excludetraindate','attendanceController@excludetraindate');
Route::delete('removeindicator','attendanceController@deleteNotes');

Route::post('/addattnd', 'attendanceController@addattnd');
Route::post('/checkin_attnd', 'attendanceController@checkin');



Route::get('/migrate_attnd', 'attendanceController@migrateAll');

Route::get('/migrateattnd/{id}', 'attendanceController@migrate');

Route::post('/addCourse',  'course@add' );
Route::post('/updCourse',  'course@update' );


Route::get('sendbasicemail','MailController@basic_email');
Route::get('sendhtmlemail','MailController@html_email');
Route::get('viewemailtemplate','MailController@view_Template');
Route::get('sendattachmentemail','MailController@attachment_email');

    });

    
Route::post('/hpb.register',  'training@addtraineeapi' );
Route::post('/hpb.registerint',  'training@addtraineeintapi' );
Route::get('/hpb.checkreg/{pk}/{id?}',  'training@checkregisterapi' );
Route::get('/hpb.getuser/{pk}',  'training@getuserapi' );

Route::get('/hpb.getcourse',  'course@get' );
//Route::get('/hpb.getraining/{id?}','training@getCourseTrain' );
Route::get('/hpb.getraining/{code}/{year?}/{month?}','training@getCourseTrain' );
Route::get('/hpb.gettraindtl/{id}','training@getTraineeDtlAPI' );



Route::get('/hpb.getcourseName/{id?}','course@getCourseName' );
Route::get('/hpb.getcourselist/{ic?}','certsController@getList' );

Route::get('/hpb.getfile/{dir}', 'certsController@getfile');
