<?php
session_start();
function amsprogrampage_function( $slug ) {
    ob_start();  

global $post, $wp, $wpdb;
$post_id = get_the_ID();
$post = get_post($post_id);
$blockdata = parse_blocks($post->post_content);
foreach($blockdata as $amsblock) 
{
    if($amsblock['blockName'] == "wpdams-amsnetwork-programpage/amsnetwork-block-programpage")
    { 
        $amsprogramid = $amsblock['attrs']['amsprogramid'];
        $instructors = $amsblock['attrs']['instructors'];
        $member = $amsblock['attrs']['member'];
        $nonmember = $amsblock['attrs']['nonmember'];
        $earlybird = $amsblock['attrs']['earlybird'];
        $registerbtn = $amsblock['attrs']['showhideurl'];
        $registerurl = $amsblock['attrs']['register_url'];
        $register_urltab = $amsblock['attrs']['register_urltab'];
        $showhideurl = $amsblock['attrs']['showhideurl'];
    }
}
?>
<div class="container-wrap">

<?php

$bgcolor = get_option('wpams_button_colour_btn_label');
if(empty($bgcolor))
{
    $bgcolor = "#337AB7";
}
$blocks = parse_blocks($post->post_content);
// Register URL
if($registerurl)
{
    $registerurl = $registerurl;
}
else
{
    $apiurl = get_option('wpams_url_btn_label');
    $registerurl = "https://".$apiurl.".amsnetwork.ca/login";
}

$arrayResult = get_programdetails($amsprogramid);
$event_image = isset($arrayResult['program']['photo']['photo']) ? $arrayResult['program']['photo']['photo']['url'] : '';
$thumbnailimage = get_the_post_thumbnail_url( $post_id);
 // New changes
$usersData = get_amsmemberlogindata($_SESSION['accesstoken'], $_SESSION['user_id']);
$subdomain = get_option('wpams_url_btn_label');
if(isset($usersData) && !empty($usersData)):
    $logindata = $usersData['user'];
    $organization_id = isset($logindata) ? $logindata['organization_id'] : 0;
    $account_id = isset($logindata) ? $logindata['account_id'] : 0;
    $filter = 'current';
    $program_id = $amsprogramid;
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

$eventwindow = $register_urltab;
if(empty($eventwindow))
{
    $eventwindow = "_self";
}

if(!empty($_SESSION['accesstoken'])){
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
}

?>


   <div class="site-content"> <!-- site-content" -->
    <div class="container no-sidebar ams-content">
        <div class="wrap">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main"> <!-- Entry content -->
                    <div class="entry-content">
                        <div class="wp-block-columns main-content main-content-three-col">
                            <div class="categorysearchdata right-col">
                                <div class="eventdetail"> 
                                    <input type="hidden" id="getpageid" value="8273">
                                    <div class="event-img-sec">
                                        <?php if($thumbnailimage!=''){ ?>
                                        <div class="img-sec">
                                            <img src="<?php echo $thumbnailimage; ?>">
                                        </div>
                                        <?php }elseif($event_image!=''){ ?>
                                        <div class="img-sec">
                                            <img src="<?php echo $event_image; ?>">
                                        </div>
                                        <?php }else{ $defaultImage = esc_url( plugins_url( 'assets/img/bg-image.png', dirname(__FILE__) ) ); ?>
                                        <div class="img-sec">
                                            <img src="<?php echo $defaultImage; ?>">
                                        </div>
                                        <?php } ?>
                                        <div class="ing-title">
                                            <h1><?php echo $arrayResult['program']['name']; ?></h1>
                                             <?php if (isset($member) && isset($nonmember) && isset($earlybird) && empty($member) && empty($nonmember) && empty($earlybird)){ ?>
                                                <div class="event-description-sec">
                                                    <div class="text-sec">
                                                        <p class="text-italic">
                                                            <?php echo $arrayResult['program']['description']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php if(!isset($member) && empty($member)){ ?>
                                            <div class="enrollment enrtop">
                                                <h3>Member Enrollment Price</h3><p> $<?php echo $arrayResult['program']['member_enrollment_price']; ?></p>
                                            </div>
                                            <?php } ?>
                                            <?php if(!isset($nonmember) && empty($nonmember)){ ?>
                                            <div class="enrollment">
                                                <h3>Non MemberPrice</h3><p> $<?php echo $arrayResult['program']['enrollment_price']; ?></p>
                                            </div>
                                            <?php } ?>
                                            <?php if(!isset($earlybird) && empty($earlybird)){ ?>
                                            <div class="enrollment">
                                                <h3>Earlybird Discount</h3><p> $<?php echo $arrayResult['program']['earlybird_discount']; ?></p>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="event-detail-sec">
                                        <div class="left-sec">
                                            <h1></h1><h2>About this Event</h2>
                                             <div class="text-sec">
                                                <?php if (!isset($member) || !isset($nonmember) || !isset($earlybird)){ ?>
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
                                                $eventtime = get_eventscheduletime($amsprogramid);
                                                 
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
                                                            echo "<div class='daytimediv'>";    
                                                                echo  "<div class='evtdate'>";
                                                                        echo "<p>".date('D, M d, Y', strtotime($daytime['start']))."</p>";
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
                                                        if (!isset($showhideurl))
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
                                                        if (!isset($showhideurl))
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
                                            <?php if(isset($programData) && !empty($programData)){ ?>
                                                
                                            <div class="location-sec invoicedata" id="invoicedata" <?php if($msg =='active'){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"'; } ?>>
                                                <h3>Invoice Details</h3>
                                                <?php 
                                                    if(isset($msg) && $msg =='active' && $programId == $program_id){ ?>
                                                      <p>Invoice Number - <?php echo $invoiceId; ?></p>
                                                      <p>Amount - <?php echo $invoiceamount; ?></p>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>

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
                                                if (!isset($instructors))
                                                {
                                            ?>
                                            <div class="location-sec">
                                                <h3>Instructors</h3>
                                                <p><?php echo $arrayResult['program']['instructors']; ?></p>
                                            </div>
                                            <?php 
                                                }
                                            } 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .entry-content -->
                    </div>
                </main><!-- #main -->
            </div><!-- #primary -->
        </div>
    </div><!-- .container no-sidebar -->
</div>
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
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}

add_shortcode('amsprogrampage', 'amsprogrampage_function');

?>