<?php

get_header();  
session_start();
?>
<div class="container-wrap">
    <div class="site-content"> <!-- site-content" -->
     <div class="container no-sidebar ams-content">
      <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
        <!-- Entry content -->
        <div class="entry-content">
            <div class="wp-block-columns main-content main-content-three-col" >
                <div class="categorysearchdata right-col">
                    <div class="eventdetail">
                    <?php
                    global $wp, $wpdb;
                    $alleventid = $wp->query_vars['eventslug'];
                    $arrayevid = explode("-",$alleventid);
                    
                    $bgcolor = get_option('wpams_button_colour_btn_label');
                    if(empty($bgcolor))
                    {
                        $bgcolor = "#337AB7";
                    }

                    $arrayResult = get_eventlisting($arrayevid[1]);

                    // New changes
                    $usersData = get_amsmemberlogindata($_SESSION['accesstoken'], $_SESSION['user_id']);
                    $subdomain = get_option('wpams_url_btn_label');
                    if(isset($usersData) && !empty($usersData)):
                        $logindata = $usersData['user'];
                        $organization_id = isset($logindata) ? $logindata['organization_id'] : 0;
                        $account_id = isset($logindata) ? $logindata['account_id'] : 0;
                        $filter = 'current';
                        $program_id = $arrayevid[1];
                        $programData = get_amsprogramdata($_SESSION['accesstoken'], $program_id);
                        $programUsers = isset($programData['users']) ? $programData['users'] : [];
                    else:
                        $logindata = [];
                    endif;

                    foreach($programUsers as $key=>$uservalue)
                    {
                        if($uservalue['id'] == $_SESSION['user_id'])
                        {
                          $msg = 'active';
                        }
                    }


                    $invoices = invoicesData($account_id,$_SESSION['accesstoken'],$programData['name']);
                    $data = json_decode($invoices);
                    if(!empty($data)){
                      $allData = $data->invoicedata;
                    }
                    $incId = isset($allData) ? $allData->id : '';
                    $registerId = isset($allData) ? $allData->registration_id : '';
                    $programId = $program_id;
                    $invoiceId = isset($allData) ? $allData->reference : '';
                    $invoiceamount = isset($allData) ? $allData->total_due_cached : '';
                    // End new changes

                    
                    //
                    $post = get_post($arrayevid[0]);
                    $blocks = parse_blocks($post->post_content);

                    // Register URL
                    if($blocks[0]['attrs']['register_url'])
                    {
                        $registerurl = $blocks[0]['attrs']['register_url'];
                    }
                    else
                    {
                        $apiurl = get_option('wpams_url_btn_label');
                        $registerurl = "https://".$apiurl.".amsnetwork.ca/login";
                    }

                    $eventwindow = $blocks[0]['attrs']['register_urltab'];
                    if(empty($eventwindow))
                    {
                        $eventwindow = "_self";
                    }
                ?>    
                    <input type="hidden" id="getpageid" value="<?php echo $arrayevid[1]; ?>">
                        <div class="event-img-sec">
                            <div class="img-sec">
                                <?php
                                if($arrayResult['program']['photo']['photo']['medium']['url'] == NULL || $arrayResult['program']['photo']['photo']['medium']['url'] == "")
                                {  
                                    // Check if organization toogle is ON
                                  if (isset($blocks[0]['attrs']['organizationevents']))
                                  { 
                                    if(empty($arrayResult['program']['organization_logo']))
                                    {
                                      echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ ) .">";
                                    }
                                    else 
                                    {
                                      echo "<img class='organizationlogo' src=".$arrayResult['program']['organization_logo'] .">";
                                    }

                                  }
                                  else
                                  {
                                    echo "<img src=".plugins_url( 'assets/img/bg-image.png', __FILE__ ) .">";
                                  }
                                }
                                else
                                { 
                                ?>
                                <img src="<?=$arrayResult['program']['photo']['photo']['medium']['url']?>">
                                <?php } ?>
                            </div>
                            <div class="ing-title">
                                <?php
                                if($arrayResult['program']['status_string'] == "Cancelled")
                                {
                                    echo "<span class='statusstring'>Event Cancelled</span>";
                                }
                                elseif ($arrayResult['program']['status_string'] == "Finished") {
                                    echo "<span class='statusstring'>Event Finished</span>";
                                }
                                ?>

                                <h1><?=$arrayResult['program']['name']?></h1>

                                <?php if (isset($blocks[0]['attrs']['member']) && isset($blocks[0]['attrs']['nonmember']) && isset($blocks[0]['attrs']['earlybird']) && empty($blocks[0]['attrs']['member']) && empty($blocks[0]['attrs']['nonmember']) && empty($blocks[0]['attrs']['earlybird'])){ ?>
                                    <div class="event-description-sec">
                                        <div class="text-sec">
                                            <p class="text-italic">
                                                <?php echo $arrayResult['program']['description']; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if(!empty($arrayResult['program']['member_enrollment_price']))
                                { 
                                    if (!isset($blocks[0]['attrs']['member']))
                                    {
                                ?>
                                <div class="enrollment enrtop">
                                    <h3>Member Enrollment Price</h3>
                                    <p>
                                        <?php
                                        if($arrayResult['program']['member_enrollment_price'] == 0)
                                        {
                                            echo "Free";
                                        }
                                        else
                                        {
                                            echo "$". $arrayResult['program']['member_enrollment_price'];
                                        }

                                        ?>
                                        
                                    </p>
                                </div>
                                <?php
                                    }
                                }
                                if(!empty($arrayResult['program']['enrollment_price']))
                                {
                                    if (!isset($blocks[0]['attrs']['nonmember']))
                                    { 

                                        if($arrayResult['program']['enrollment_price'] > 0)
                                        {
                                ?>
                                <div class="enrollment">
                                    <h3>Non MemberPrice</h3>
                                    <p>
                                        <?php
                                        if($arrayResult['program']['enrollment_price'] == 0)
                                        {
                                            echo "Free";
                                        }
                                        else
                                        {   
                                            

                                                echo "$". $arrayResult['program']['enrollment_price'];
                                            
                                        }
                                        ?>    
                                    </p>
                                </div>
                                <?php 
                                        }
                                    }
                                }
                                if(!empty($arrayResult['program']['earlybird_discount']))
                                {
                                    if (!isset($blocks[0]['attrs']['earlybird']))
                                    {
                                 ?>
                                <div class="enrollment">
                                    <h3>Earlybird Discount</h3>
                                    <p>
                                        <?php
                                        if($arrayResult['program']['earlybird_discount'] == 0)
                                        {
                                            echo "Free";
                                        }
                                        else
                                        {
                                            echo "$". $arrayResult['program']['earlybird_discount'];
                                        }
                                        ?>    
                                    </p>
                                </div>
                                <?php 
                                    }
                                } 
                                ?>

                            </div>
                        </div>

                        <div class="event-detail-sec">
                            <div class="left-sec">
                                <h1></h1>
                                <h2>About this Event</h2>
                                <div class="text-sec">
                                    <?php if (!isset($blocks[0]['attrs']['member']) || !isset($blocks[0]['attrs']['nonmember']) || !isset($blocks[0]['attrs']['earlybird'])){ ?>
                                    <p class="text-italic">
                                        <?php echo $arrayResult['program']['description']; ?>
                                    </p>
                                    <?php } ?>
                                    <p class="text-italic">
                                        <?php
                                            if($arrayResult['program']['program_details'])
                                            {
                                                echo $arrayResult['program']['program_details']; 
                                            }
                                        ?>
                                    </p>
                                </div>
                            </div>
                            

                            <div class="right-sec">
                                <div class="date-time-sec">
                                    <?php
                                    //$date = $arrayResult['program']['created_at'];
                                    $date=date_create($arrayResult['program']['created_at']);
                                    
                                    ?>
                                    <h3>Date And Time</h3>
                                </div>
                                
                                <?php 
                                    $eventtime = get_eventscheduletime($arrayevid[1]);
                                     
                                    if(isset($eventtime['json']['scheduled_program_dates']))
                                    {    
                                        $keys = array_keys($eventtime['json']['scheduled_program_dates']);
                                         $lastKey = $keys[count($keys)-1];
                                         
                                        $total = count($keys);
                                        $start=date_create($eventtime['json']['scheduled_program_dates'][$lastKey]['start']);
                                        $end=date_create($eventtime['json']['scheduled_program_dates'][$lastKey]['end']);

                                        if($total > 1)
                                        {
                                            echo "<div class='ragister-sec'>";
                                                echo "<div class='classevent'>"; 
                                                foreach ($eventtime['json']['scheduled_program_dates'] as $key => $daytime) { 
                                                $eventdate = localtimezone('D, M d, Y',$daytime['start']);      
                                                echo "<div class='daytimediv'>";    
                                                    echo  "<div class='evtdate'>";
                                                            echo "<p>".$eventdate."</p>";
                                                    echo  "</div>";
                                                    
                                                $starttime = localtimezone('H:i a',$daytime['start']);
                                                $endtime = localtimezone('H:i a',$daytime['end']);

                                                    echo "<div class='time'>";
                                                    echo "<p>".$starttime. " – ".$endtime. "</p>";
                                                    echo "</div>";
                                                echo "</div>";    
                                                }
                                                echo "</div>";
                                            echo   "<br>";
                                            if (!isset($blocks[0]['attrs']['showhideurl']))
                                            {
                                                echo   "<div class='reg-sec 3 location-sec'>";
                                                if(isset($_SESSION['accesstoken']) && $_SESSION["user_id"]):
                                                    echo "<div class='registerBtn'>";
                                                        if(isset($msg) && $msg =='active' ){ 
                                                            if($invoiceId != '' && $programId == $program_id){
                                                            echo "<a href='https://".$subdomain.".amsnetwork.ca/invoices/".$incId."/online_payment' id='paymentBtn' style='background-color:".$bgcolor."' target='_blank' class='btn ml-1'>Payment</a>";
                                                            }
                                                            echo "<a href='javascript:void(0);' style='background-color:".$bgcolor."' id='registerBtn' class='btn ml-1'>Registered</a>";
                                                        }
                                                        else
                                                        {
                                                                
                                                        echo "<a href='javascript:void(0);' id='registerBtn' style='background-color:".$bgcolor."' class='btn ml-1' onclick='registerProgram()'>Register</a>";
                                                        }
                                                        
                                                    echo "</div>";
                                                else:
                                                    echo   "<a href=".$registerurl." style='background-color:".$bgcolor."' target=".$eventwindow.">Register</a>";
                                                endif;   
                                                    echo "<div class='post-group customloader' id='inifiniteLoader' style='text-align: center; display: none;'>
                                                        <img src=".plugins_url( 'assets/img/buttonloader.gif', __FILE__ )." >
                                                      </div>"; 
                                                    echo "<br>";  
                                                    echo "<div id='verifystatusMsg' class='verifystatus'>";  
                                                echo   "</div>";    
                                            }
                                            echo "</div>";
                                        }
                                        else
                                        {
                                           

                                            echo "<div class='ragister-sec'>";
                                            if (!isset($blocks[0]['attrs']['showhideurl']))
                                            {
                                            echo   "<div class='reg-sec 1'>";
                                            echo   "<a href=".$registerurl." target=".$eventwindow." style='background-color:".$bgcolor."'>Register</a>";
                                            echo   "</div>";
                                            }
                                                
                                            echo  "<div class='evtdate'>";
                                                    echo "<p>".date_format($start,"D, M d, Y")."</p>";
                                            echo  "</div>";
                                                
                                            echo "<div class='time'>";
                                            echo "<p>".date_format($start,"H:i"). " – ".date_format($end,"H:i"). "</p>";
                                            echo "</div>";
                                            echo "</div>";
                                        }
                                    }
                                ?>
                                <?php if(isset($programData) && !empty($programData)): ?>
                                    
                                <div class="location-sec invoicedata" id="invoicedata" <?php if($msg =='active'){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"'; } ?>>
                                    <h3>Invoice Details</h3>
                                    <?php 
                                        if(isset($msg) && $msg =='active' && $programId == $program_id){ ?>
                                          <p>Invoice Number - <?php echo $invoiceId; ?></p>
                                          <p>Amount - <?php echo $invoiceamount; ?></p>
                                    <?php } ?>
                                </div>
                                <?php endif; ?>

                                <div class="location-sec">
                                    <h3>Location</h3>
                                    <p>
                                        <?php if(!empty($arrayResult['program']['location']))
                                        {
                                            echo $arrayResult['program']['location'];
                                        }
                                        else
                                        {
                                            echo "No Location Found";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php
                                if(!empty($arrayResult['program']['instructors']))
                                {
                                    if (!isset($blocks[0]['attrs']['instructors']))
                                    {
                                ?>
                                <div class="location-sec">
                                    <h3>Instructors</h3>
                                    <p><?=$arrayResult['program']['instructors']?></p>
                                </div>
                                <?php 
                                    }
                                } 
                                ?>
                            </div>
                            
                        </div>
                    </div>  
                </div> 
            </div>
        </div>
        <!-- .entry-content -->


            </main><!-- #main -->
        </div><!-- #primary -->
      </div>  
     </div><!-- .container no-sidebar -->
    </div><!-- .site-content -->
</div>

<script type="text/javascript">

 //start function for use of register user for specific program
 function registerProgram() 
 {
    var user_id = '<?php echo $_SESSION["user_id"]; ?>';
    var program_id = jQuery("#getpageid").val();
    var access_token = '<?php echo $_SESSION["accesstoken"]; ?>';
    jQuery.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       cache: false,
       data: { 
              action: 'programRegistration',
              user_id:user_id,
              program_id:program_id,
              access_token:access_token
             },
       dataType: 'JSON',
       beforeSend: function(){
       // Show image container
          jQuery("#inifiniteLoader").show();
          //jQuery("#btnSubmit").attr("disabled", true);
       },
       success: function (data) {
          var mydata = data.msg;
           if(mydata == 'valid'){
              jQuery(".registerBtn a#registerBtn").text('Registered');
              jQuery(".registerBtn a#registerBtn").attr("onclick", "").unbind("click");
              var account_id = '<?php echo $account_id; ?>';
              var filter = 'current';
              var access_token = '<?php echo $_SESSION["accesstoken"]; ?>';
              var event_title = '<?php echo $programData['name']; ?>';
              var bgcolor = '<?php echo $bgcolor; ?>';
                jQuery.ajax({
                   url: amsjs_ajax_url.ajaxurl,
                   type:'POST',
                   cache: false,
                   data: { 
                          action: 'invoicesData',
                          account_id:account_id,
                          filter:filter,
                          access_token:access_token,
                          event_title:event_title
                         },
                   dataType: 'JSON',
                   success: function (response) {
                    console.log(response);
                    jQuery("#inifiniteLoader").hide();
                    
                    var programId = program_id;
                    var invoicesData = response.invoicedata;
                    var registerId = invoicesData.registration_id;
                    var invoiceId = invoicesData.reference;
                    var status = invoicesData.status_string;
                    var invoiceamount = invoicesData.total_due_cached;
                    jQuery('#invoicedata').html('<h3>Invoice Details</h3><p>Invocie Number -'+invoiceId+'</p><p>Amount :-'+invoiceamount+'</p>');
                    jQuery('#invoicedata').show();
                    var html = '<a href="javascript:void(0);" style="background-color:#337AB7" id="paymentBtn" class="btn ml-1">Payment</a>';
                    jQuery('.registerBtn').prepend(html);
                   }
                });
           } else{
              //jQuery("#inifiniteLoaderRegisration").hide();
              alert('Something went wrong try again!');
           }
       }//end of success
    });
 }
 //end of function

</script>    
<?php
get_footer();
