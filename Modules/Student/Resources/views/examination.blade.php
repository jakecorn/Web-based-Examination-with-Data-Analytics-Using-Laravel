 <?php
 $type= array("mul"=>"Multiple Choice","mat"=>"Matching Type","tru"=>"True or False","ide"=>"Identification","ess"=>"Essay");
 use Modules\Student\Http\Controllers\StudentController;
 ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <script src="/js/app.js"></script>
        <script src="/js/jquery.countdown.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/app.css">
        <link rel="stylesheet" type="text/css" href="/css/mystyle.css">
        <script type="text/javascript" src="/js/sweetalert2@8.js"></script>
        <title>Exam / On-going</title>
    </head>
    <body> 
      <div class="container-fluid">

        @include('base::inc.error')
        <div class="row">
          
            <br>
              <div class="col-sm-12">


                

                <div class="sheet">
                  
                  <div class="sheet-header" style="text-align:center">
                    <h4>Negros Oriental State Unviversity</h4>
                    <h5>Bayawan-Sta Catalina Campus</h5>
                    <br>
                    <div id="profile" class="d-none d-block-lg">
                      <img src="{{Auth::user()->photo}}" onerror="this.src='/uploads/images/user.png'" style="border:1px solid gray;border-radius:20px;width:200px"/>	   
                    </div>
                    
                    <b>{{$subject[0]->sub_code}}
                    {{$subject[0]->sub_desc}}</b>
                    <br>
                    {{$subject[0]->day}} - {{$subject[0]->time}}

                    <br>
                    <?php
                      if($examination[0]->exam_type==1){
                          echo $subject[0]->type." Examination";
                      }else{
                          echo "Quiz<br>";
                      }

                    ?>
                    <span style="text-transform:capitalize">{{$subject[0]->semester}} Semester, S.Y. {{$subject[0]->sy}}<br></span>

                    {{$teacher[0]->name}}<br>Subject Instructor<br>


                  </div>
                  <?php
                    if(session('done')){?>
                      @include('student::exam.examexitmessage')
                      <?php return false;
                    }
                  ?>
                  <br>
                  <br>
                  <div>
                    <b>{{$examination[0]->gen_instruction}}</b>
                    <span style="display: none" id="durationContainer">{{$examination[0]->duration}}</span>
                  </div>  
                <br>

                <b>Part {{$exam_part[0]->part_num}}. {{$type[$exam_part[0]->exam_type]}}<br></b>
                {{$exam_part[0]->exam_instruction}}<br><br>

                   @if($exam_part[0]->exam_type=="mat")
                    @include('student::exam.question_mat')
                  @else
                    @include('student::exam.question_mul')
                    
                  @endif
                    <center>
                        <nav aria-label="...">
                          <ul class="pagination">
                            <!-- <li class="page-item disabled">
                              <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li> -->
                            <?php

                                $part_num_seq = "";     

                                foreach ($question_list as $key => $value) {
                                    $answered_question = 'not-answered-question';
                                    if($value->answer!="" || $value->long_answer!=""){
                                        $answered_question = "answered-question";
                                    }

                                    $part_num_seq_loop = StudentController::numberToRomanRepresentation($value->part_num);
                                    
                                    $part_num_seq_display = "";   

                                    $active ='';

                                    if($position == $value->position && $value->part_num == $part_num){
                                        $active ='active';
                                    }
                                   
                                    if($part_num_seq_loop!=$part_num_seq){
                                        $part_num_seq = $part_num_seq_loop;
                                        $part_num_seq_display = $part_num_seq;
                                         echo "<li class='page-item'><span style='font-family:times new roman;font-weight:bold;background:#e0dede;color:gray' >".$part_num_seq_display.".</span></li>";
                                    }
                                    
                                    echo "<li class='page-item ".$active."''><a class='page-link ".$answered_question."' href='/student/exam/".$class_record_id."/".$examination[0]->id."/start/part/$value->part_num/position/$value->position'><span style='font-family:time new roman'>".$value->position."</a></li>";
                                }
                              ?>
                            
                            
                            <!-- <li class="page-item">
                              <a class="page-link" href="#">Next</a>
                            </li> -->
                          </ul>
                        </nav>
                    </center>
                  
                </div> <!-- end sheet -->
                
                <br>
              </div>

        </div>
      </div>
      
      <div id="timerContainer" style=""><span id="timer"></span></div>
      
      <div id="expirationModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
              <div class="modal-header">
                 
                  <h4 class="modal-title"><a class="fa fa-warning alert-warning margin-right"></a>Time Duration</h4>
              </div>
              <div class="modal-body">
                  <p>Time duration has ended. Your answer will be submitted automacilly.</p>
              </div>
              <div class="modal-footer">
                  <a class="btn btn-primary btn-ok-delete width-n" href="" onclick="yes()">View Score</a>
               </div>
          </div>
        </div>
    </div>
    <button data-toggle="modal" style="display:none" id="expirationModal_button" data-target="#expirationModal"></button>
    </body>
</html>
<!-- Display the countdown timer in an element -->
<script>
    $(document).ready(function() {

      var hasTriggered = false;
      var endDate =new Date().getTime()+{{$end_time_millisecond*1000}};

      if($('#durationContainer').html()!="None"){
        $('#timer').countdown(endDate).on('update.countdown', function(event) {
            var format = '%H:%M:%S';
            var hours = event.offset.hours;
            var minutes = event.offset.minutes; 

            if(hours == 0 && minutes<=4){
              if(!hasTriggered){
                hasTriggered = true;
                aboutToExpire($(this));
              }
            }

            $(this).html(event.strftime(format));

        }).on('finish.countdown', function(event) {
          $(this).html("COMPLETED");

          $(this).html('00:00:00').parent().addClass('disabled expired');
          $('#expirationModal_button').click();

        });
      }

      $(".question-form input[type='radio']").change(function(){
        $.post('', $('.question-form').serialize())
      });

      if($('.not-answered-question').length==0){
        $('.answered').show();
      }

      $('.answered').click(function(){
        swal.fire({
          title: 'Are you sure?',
          text: "You want to submit your answers?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, Submit it',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
          }).then((result) => {
          if (result.value) {
            window.location="{{route('submitscore',$examination[0]->id)}}";
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            return false;
          }
        });
      });

    });

    

    function aboutToExpire(el){     
      
      el.css("color", "red").fadeOut(500).fadeIn(500, function(){
        aboutToExpire(el);
      });
    }
    
 </script> 
<style type="text/css">
    #timerContainer{
        padding:8px;opacity:0.5;color:green;position:fixed;left:20px;top:20px
    }

    #timerContainer:hover{
        opacity: 1
    }
    #profile {
      position:fixed;top:60px;right:50px
    }

    @media (max-width: 768px) {
      #timerContainer {
        top:20px;
        left:15px;
        padding:0 0 0 5px;
      }

      #profile {
        width:30%;
        top:20px;
        right:15px;
      }
      #profile img {
        max-width: 100%;
        border:1px solid red;
      }

    }
    .answered-question{
        color: #155724 !important;
        background-color: #d4edda !important;
        z-index: 3;
    }

    .active .answered-question{
        color: none;
        background-color: none
        z-index: 3;
    }

    
    .expired span{
        color:red;
    }
</style>