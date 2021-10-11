document.addEventListener('init', function(event) {
  var page = event.target;
  if (page.matches('#helloworld-page')) {
   // page.querySelector('ons-toolbar .center').innerHTML = 'My app';
    page.querySelector('ons-button').onclick = function() {
      ons.notification.alert('Hello world!');
    };
      
      
    /*  
      $("#addtraining").submit(function(e){
    e.preventDefault();
          
    var formData = $(this).serialize();
          
          $("#collapse1").collapse("hide");
         
    $.ajax({
            type: "POST",
            url: "trainadd",
            data: formData,
            success: function(data){
                if(data.status==="PASS"){
                    //quikmodal.notify({message:data.message},{type:"success"});
                }
                else{
                    //quikmodal.notify({message:data.message},{type:"danger"});
                }
            }
    }); 

});
      */
  }
});

$(".modal").on("shown.bs.modal", function(e) {

    $('.modal-backdrop').removeAttr('class');

});


$("#punch").submit(function(e){
    e.preventDefault();
    var formData = $(this).serialize();
    var url=$(this).attr("action");
    $("#traningPunch").modal("hide")
     $.getJSON(url+"?"+formData,  function(data){
                if(data.status==="PASS"){
                    //location.reload();
                      ons.notification.alert(data.message);
                }
                else{
                      ons.notification.alert(data.message);
                }
            });

});


function openMenu(){
     var menu = document.getElementById('sidebar');
  menu.open();
}

$(document).ready(function() {
    $("#summernote").summernote();
});

$("#edtUser").on("show.bs.modal", function (e) {
    //get data-question attributes of the clicked element
    let userid = $(e.relatedTarget).data('userid');
    //populate the input
    $(e.currentTarget).find('input[name="userid"]').val(userid);

});

$("#edtCourse").on("show.bs.modal", function (e) {
    //get data-question attributes of the clicked element
    
    let id = $(e.relatedTarget).data('courseid');
    let name = $(e.relatedTarget).data('coursename');
    let desc = $(e.relatedTarget).data('coursedesc');
    let fee = $(e.relatedTarget).data('coursefee');
    let courseLink = $(e.relatedTarget).data('courselink');
    
    //populate the input
    $(e.currentTarget).find('input[name="courseid"]').val(id);
    $(e.currentTarget).find('input[name="courseName"]').val(name);
    $(e.currentTarget).find('input[name="courseLink"]').val(courseLink);
    $(e.currentTarget).find('input[name="courseFee"]').val(fee);
    $(e.currentTarget).find('textarea[name="courseDesc"]').val(desc);
    
    $("#courseDesc").summernote();

});


$("#edtTrain").on("show.bs.modal", function (e) {
    //get data-question attributes of the clicked element
    

    let trainid = $(e.relatedTarget).data('trainid');
    let coursefk = $(e.relatedTarget).data('coursefk');
    let trainname = $(e.relatedTarget).data('trainname');
    let traindetail = $(e.relatedTarget).data('traindetail');
    let trainplace = $(e.relatedTarget).data('trainplace');
    let traindiscnt = $(e.relatedTarget).data('traindiscnt');
    let trainaddress = $(e.relatedTarget).data('trainaddress');
    let trainstate = $(e.relatedTarget).data('trainstate');
    let trainstart = $(e.relatedTarget).data('trainstart');
    let trainend = $(e.relatedTarget).data('trainend');
    let isfulltime = $(e.relatedTarget).data('isfulltime');
    //populate the input
    $(e.currentTarget).find('input[name="trainPK"]').val(trainid);
    $(e.currentTarget).find('select[name="courseFK"]').val(coursefk);
    $(e.currentTarget).find('input[name="trainName"]').val(trainname);
    $(e.currentTarget).find('input[name="trainPlace"]').val(trainplace);
    $(e.currentTarget).find('input[name="trainDiscnt"]').val(traindiscnt);
    $(e.currentTarget).find('textarea[name="trainAddress"]').val(trainaddress);
    $(e.currentTarget).find('select[name="trainState"]').val(trainstate);
    $(e.currentTarget).find('input[name="trainDtStart"]').val(trainstart);
    $(e.currentTarget).find('input[name="trainDtEnd"]').val(trainend);
    $(e.currentTarget).find('textarea[name="trainDetail"]').val(traindetail);
    if(isfulltime=="1"){
        $(e.currentTarget).find('input[id="ck1"]').attr("checked", true);
        $(e.currentTarget).find('input[id="ck2"]').attr("checked", false);
    }
    else{
        $(e.currentTarget).find('input[id="ck1"]').attr("checked", false);
    $(e.currentTarget).find('input[id="ck2"]').attr("checked", true);}


    $("#trainDetail").summernote();

});