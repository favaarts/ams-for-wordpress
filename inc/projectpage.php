<?php
session_start();
function amsprojectpage_function( $slug ) {
    ob_start();  

global $post;
$pageid = $post->ID;
$post_slugpage = $post->post_name;

//$blockdata = get_sidebaroption();

$post_id = get_the_ID();
$post = get_post($post_id);
$blockdata = parse_blocks($post->post_content);

/*echo "<pre>";
print_r($blockdata);
echo "</pre>";*/

foreach($blockdata as $amsblock) 
{
    if($amsblock['blockName'] == "wpdams-amsnetwork-projectpage/amsnetwork-block-projectpage")
    { 
        $amsprojectid = $amsblock['attrs']['amsprojectid'];
        $project_protected = $amsblock['attrs']['project_protected'];

        $firstpartmailtext = $amsblock['attrs']['firstpartmailtext'];
        $secondpartmailtext = $amsblock['attrs']['secondpartmailtext'];
        $remove_viewmore = $amsblock['attrs']['remove_viewmore'];
        $project_paymenturl = $amsblock['attrs']['project_paymenturl'];
        $project_paymentmessage = $amsblock['attrs']['project_paymentmessage'];
        $paymentbuttonname = $amsblock['attrs']['paymentbuttonname'];

        if(isset($amsblock['attrs']['amsprojectpagesidebar']))
        {
            $amsprojectpagesidebar = 1;
        }
        else
        {
            $amsprojectpagesidebar = "";
        }

        $projectsynopsis = $amsblock['attrs']['projectsynopsis'];
        $projectmedia = $amsblock['attrs']['projectmedia'];
        $projectcrewroles = $amsblock['attrs']['projectcrewroles'];
        $projectlongattributes = $amsblock['attrs']['projectlongattributes'];
        $projectshortattributes = $amsblock['attrs']['projectshortattributes'];
        $bannercrewroles = $amsblock['attrs']['bannercrewroles'];
        $projecttitle = $amsblock['attrs']['projecttitle'];
    }
}    

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/hls.js/0.5.14/hls.min.js"></script>
<script type="text/javascript" src="https://d2nvlqutlc7e9k.cloudfront.net/jwplayer.js"></script>
<script>jwplayer.key="KfEj20iL73YJlQBSm+6bqoSc148Cf5eSvIn2BWoo/Zg=";</script>
<div class="container-wrap">

<?php

global $wp, $wpdb;


$arrayResult = get_projectdetails($amsprojectid);

//echo $arrayResult['project']['user_id'];
/*echo "<pre>";
print_r($arrayResult);
echo "</pre>";
*///echo $project_protected;

$crewrole = "Crew%20Role";
$attributeCrewResult = get_projectattributes($amsprojectid,$crewrole);

$photo = "Media%20Type";
$attributePhoto = get_projectattributes($amsprojectid,$photo);

$longattributes = "Long%20Attributes";
$longAttribute = get_projectattributes($amsprojectid,$longattributes);


$loginPageURL=site_url($post_slugpage);
/**/

if(isset($firstpartmailtext))
{
    $firstpartmailtext = $firstpartmailtext;
}
else
{
    $firstpartmailtext = "Thank you for supporting our festival. Please use this acces credentilas to watch the content.";
}

if(isset($secondpartmailtext))
{
    $secondpartmailtext = $secondpartmailtext;
}
else
{
    $secondpartmailtext = "I you have any questions or coments please contact us at programing@fava.ca.";
}
/**/

$bgcolor = get_option('wpams_button_colour_btn_label');
if(empty($bgcolor))
{
    $bgcolor = "#337AB7";
}

/*echo "<pre>";
print_r($blocks);
echo "</pre>";*/

//

if(isset($_SESSION["projectpassword"]) || empty($project_protected) )
{
    if($nowtime > $_SESSION['expire'])
    {
      session_unset();
      session_destroy();
    }  
    else
    {
?>


    <div class="site-content"> <!-- site-content" -->
     <div class="container no-sidebar ams-content">
      <div class="wrap">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
        <!-- Entry content -->
        <div class="entry-content">
            <div class="wp-block-columns main-content main-content-three-col">
                <div class="categorysearchdata right-col">

                    <?php
                    if(isset($_SESSION["username"]))  
                      {
                        echo "<div class='amsuserlayout'>";
                            echo "<div class='userlogin'><p>Hii, ".$_SESSION["username"]."</p></div>";
            
                            echo "<div class='amslogoutbutton'>
                                    <input type='submit' id='btnAMSLogout' onclick='btnAMSLogout()' value='Log Out'>
                                  </div>";
                                  
                        echo "</div>";                              
                        
                      }
                    ?>
                    <div class="projectdetail">
                        
                        <div class="project-img-sec">

                            <div class="img-sec video-main">
                                <?php
                                if(isset($attributePhoto['project_attributes']))
                                {    
                                    foreach($attributePhoto['project_attributes'] as $x_value) 
                                    {
                                        if($x_value['project_attribute_type_name'] == "Video")
                                        {
                                            if($x_value['file_attachment_thumbnail'])
                                            {
                                                $videoBanner = $x_value['file_attachment_thumbnail'];
                                                
                                                $fileAttachment = $x_value['file_attachment'];
                                               
                                                $amssinglevideonew = $x_value['value_4'];
                                                echo "<a class='video-icon'></a>";
                                                break;
                                            }
                                            else
                                            {
                                                $videoBanner = plugins_url( 'assets/img/video_poster.jpg', __FILE__ );
                                            }
                                        }
                                        elseif ($x_value['project_attribute_type_name'] == "Audio") 
                                        {
                                            $audiofileAttachment = $x_value['file_attachment'];
                                            break;
                                        }    
                                    }
                                }    

                                if(empty($amssinglevideonew))
                                {
                                    if(empty($arrayResult['project']['thumbnail']))
                                    {
                                        $videoBanner = plugins_url( 'assets/img/bg-image.png', __FILE__ );
                                        echo  "<img src=".$videoBanner." class='hover-shadow cursor'>";
                                    }
                                    else
                                    {

                                        echo  "<img src=".$arrayResult['project']['thumbnail'] ." class='hover-shadow cursor'>";
                                    }
                                }
                                
                                ?>

                                
                                <div class="videobanner" id="video_player"></div>
                                <div class="amsaudio" id="amsaudio_player"></div>
                                    
                            </div>

                            <?php
                            if (empty($amsprojectpagesidebar))
                            { 
                            ?>
                                <div class="ing-title">
                                    <?php
                                    echo  "<h1> ". $arrayResult['project']['name'] ;
                                      if($arrayResult['project']['completed_year'])
                                      {
                                        echo " (".$arrayResult['project']['completed_year'].")";
                                      }
                                      echo "</h1>";
                                    
                                    if (!isset($bannercrewroles)) 
                                    {     
                                        if($arrayResult['project']['creator'])
                                        {
                                            echo "<div class='enrollment enrtop'>
                                                <h3>Submitted By</h3>";
                                              if ($blocks[0]['attrs']['projecttomember'])  
                                              {
                                               
                                                    echo "<p><a target='_blank' href='".site_url('/members/'.$arrayResult['project']['user_id'].'-'.$projectconnectmemberid.'/details' )."'>".$arrayResult['project']['creator']."</a></p>";
                                                
                                              }
                                              else
                                              {
                                                echo "<p>".$arrayResult['project']['creator']."</p>";
                                              }
                                                
                                            "</div>";
                                        }
                                    }    

                                    if(isset($attributeCrewResult['project_attributes']))
                                    {
                                        foreach($attributeCrewResult['project_attributes'] as $x_value) 
                                        {
                                            if($x_value['project_attribute_type_name'] == "Director")
                                            {
                                                echo "<div class='enrollment'>
                                                <h3>Director(s)</h3>";
                                                if ($blocks[0]['attrs']['projecttomember'] )  
                                                {
                                                    echo "<p><a target='_blank' href='".site_url('/members/'.$x_value['value_2'].'-'.$projectconnectmemberid.'/details' )."'>".$x_value['value']."</a></p>";
                                                }
                                                else
                                                {   
                                                    echo "<p>".$x_value['value']."</p>";
                                                }
                                                echo "</div>";
                                            }
                                        } 


                                        foreach($attributeCrewResult['project_attributes'] as $x_value) 
                                        {
                                            if($x_value['project_attribute_type_name'] == "Writer")
                                            {
                                                echo "<div class='enrollment'>
                                                <h3>Writer(s)</h3>";
                                                if ($blocks[0]['attrs']['projecttomember'])  
                                                {
                                                    echo "<p><a target='_blank' href='".site_url('/members/'.$x_value['value_2'].'-'.$projectconnectmemberid.'/details' )."'>".$x_value['value']."</a></p>";
                                                }
                                                else
                                                {
                                                    echo "<p>".$x_value['value']."</p>";
                                                }
                                                echo "</div>";
                                            }
                                        }

                                        if($blocks[0]['attrs']['amsfile_attachment'])
                                        {
                                            echo "<a href=".$fileAttachment."><button class='amsvideodownload'><i class='fa fa-download'></i> Download</button></a>";
                                        }

                                    }    

                                    
                                        
                                    ?>
                                </div>
                            <?php
                            }
                            ?>    
                        </div>

                        <div class="project-detail-sec">
                            <div class="left-sec">
                                <?php
                                if (!isset($projectsynopsis))
                                {
                                    if($arrayResult['project']['synopsis'])
                                    {
                                        echo "<div class='synopsis prospace'>";
                                        //echo "<h3>Synopsis:</h3>";
                                            echo "<div class='text-sec'>";
                                                echo "<p>".$arrayResult['project']['synopsis']."</p>";
                                            echo "</div>";
                                        echo "</div>";
                                    }
                                }    
                                ?>
                                 

                                <?php
                               
                                if (!isset($projectmedia)) 
                                {    
                                    if(isset($attributePhoto['project_attributes']))   
                                    {     
                                    if(count($attributePhoto['project_attributes']) > 1)
                                    {
                                    ?> 
                                    <div class="videos prospace amsvideos">
                                       
                                        <div class="video-row">
                                            <h3>Media:</h3>
                                            <?php
                                            $i = 1;
                                            
                                            array_shift($attributePhoto['project_attributes']);

                                            foreach($attributePhoto['project_attributes'] as $x_value) 
                                            {
                                                if($x_value['project_attribute_type_name'] == "Video")
                                                {
                                                    
                                                echo "<div class='video-col'>
                                                     <div class='amsvideo-thumb'>
                                                      <img src='".$x_value['file_attachment_thumbnail'] ."'>
                                                        <a class='open-button' popup-open='popup-".$i."' href='javascript:void(0)' data-img='".$x_value['value_4']."' data-id='".$x_value['id']."'></a>
                                                        </div>
                                                        <div class='popup' popup-name='popup-".$i."'>
                                                        <div class='popup-content'>
                                                            <video id='amspopupvideo".$x_value['id']."' controls>
                                                            </video>
                                                            <a class='close-button' popup-close='popup-".$i."' href='javascript:void(0)' data-id='amspopupvideo".$x_value['id']."'>x</a>";
                                                            if($blocks[0]['attrs']['amsfile_attachment'])
                                                            {    
                                                                echo "<a href='".$x_value['file_attachment']."'><button class='amsvideodownload'><i class='fa fa-download'></i> Download</button></a>";
                                                            }
                                                    echo "</div>
                                                    </div>
                                                </div>";
                                                }
                                              $i++;      
                                            } 


                                            // Audio div
                                            foreach($attributePhoto['project_attributes'] as $ams_audio) 
                                            {
                                                if($ams_audio['project_attribute_type_name'] == "Audio")
                                                {
                                                echo "<div class='audio-col'>
                                                    <div class='amsaudio-thumbnew'>
                                                        <img class='amsaudioicon' src='".$arrayResult['project']['thumbnail']."'>
                                                        <a class='open-button' audiopopup-open='popup-".$i."' href='javascript:void(0)' data-id='".$ams_audio['id']."' data-audiourl='".$ams_audio['file_attachment']."' > </a>
                                                        
                                                    </div>
                                                    <div class='popup' audiopopup-name='popup-".$i."' style='display: none;'>
                                                        <div class='popup-content'>
                                                            <div id='player".$ams_audio['id']."'>
                                                                    
                                                                </div>
                                                         <a class='close-button' audiopopup-close='popup-".$i."' href='javascript:void(0)' data-id='player".$ams_audio['id']."'>x</a>";
                                                    echo "</div>
                                                    </div>
                                                </div>";
                                                }
                                              $i++;      
                                            }
                                            // End audio div 

                                            // Image div
                                            foreach($attributePhoto['project_attributes'] as $xphoto_value) 
                                            {
                                                
                                                if($xphoto_value['project_attribute_type_name'] == "Photo")
                                                {
                                                    echo "<div class='amsimage-col'>";

                                                    echo "<div class='column amsimagediv'>";
                                                    echo   "<img src=".$xphoto_value['file_attachment'] ." class='hover-shadow cursor'>";
                                                    echo "<a class='amsimagetag open-button' imagepopup-open='popup-".$i."' href='javascript:void(0)' data-id='".$xphoto_value['id']."' data-imageurl='".$xphoto_value['file_attachment']."' > </a>";
                                                    echo "</div>";


                                                    echo "<div class='popup' imagepopup-name='popup-".$i."' style='display: none;'>
                                                        <div class='popup-content'>
                                                            <img id='amsimage".$xphoto_value['id']."'>
                                                            <a class='close-button' imagepopup-close='popup-".$i."' href='javascript:void(0)' data-id='amsimage".$xphoto_value['id']."'>x</a>";
                                                    echo "</div>
                                                    </div>
                                                    </div>";
                                                }
                                                $i++; 
                                            }
                                            // Image div   
                                            ?>

                                        </div>
                                    </div>
                                    <?php } }  
                                }    
                                ?>
                                
                                
                                
                                

                                <?php
                                if($attributePhoto['project_attributes'][0]['project_attribute_type_name'] == "Awards/Screenings/Festivals" && !empty($attributePhoto['project_attributes'][0]['project_attribute_type_name']))
                                {
                                echo "<div class='longattributes prospace'>";
                                echo "<h3>Long Attributes:</h3>";
                                    echo "<div class='text-sec longattrval'>";
                                        echo "<p><strong>Awards/Screenings/Festivals :- </strong>";
                                    foreach($longAttribute['project_attributes'] as $x_value) 
                                    {
                                        if($x_value['project_attribute_type_name'] == "Awards/Screenings/Festivals")
                                        {
                                            echo "<span> ". $x_value['value'] . "  </span>";
                                        }
                                    }
                                        echo "</p>";
                                    echo "</div>";
                                echo "</div>";
                                }
                                ?>
                                

                                <?php
                                if (!isset($projectcrewroles)) 
                                {    
                                    if($attributeCrewResult['project_attributes'])
                                    { 
                                ?>
                                <div class="crewroles prospace">
                                    <!-- <h3>Crew Roles:</h3> -->
                                        <?php
                                        foreach($attributeCrewResult['project_attributes'] as $x_value) 
                                        {
                                            echo "<div class='rolesdiv'>";
                                                echo "<div class='typename'>".$x_value['project_attribute_type_name'] ."</div> ";
                                                echo "<div class='typevalue'>".$x_value['value'] ."</div> ";
                                            echo "</div>";
                                        }    
                                        ?>
                                </div>
                                <?php } 
                                }

                                if (!isset($projectlongattributes)) 
                                {     
                                    if($longAttribute['project_attributes'])
                                    {
                                        echo "<div class='enrollment longattribute'>";
                                                echo "<div class='text-sec'>";
                                                
                                        foreach($longAttribute['project_attributes'] as $x_value) 
                                        {
                                            if($x_value['project_attribute_type_name'] != "Synopsis")
                                            {    
                                                echo "<p> <strong>".$x_value['project_attribute_type_name']." : </strong>".$x_value['value']."</p>";  
                                            }
                                        }
                                                
                                                echo "</div>"; 
                                        echo "</div>";
                                    }                                   
                                }    
                            ?>

                            </div>
                            

                            <?php
                            $shortattribute = "Short%20Attributes";
                            $shortAttributeResult = get_projectattributes($amsprojectid,$shortattribute);

                            if (!isset($projectshortattributes)) 
                            {     
                             if($shortAttributeResult['project_attributes'])   
                             { 
                            ?>
                            <div class="right-sec">
                               
                                <!-- <div class="location-sec">
                                    <h3>Short Attributes</h3>
                                </div> -->
                                <?php
                                

                                foreach($shortAttributeResult['project_attributes'] as $x_value) 
                                {
                                    echo "<div class='location-sec'>
                                            <h4>".$x_value['project_attribute_type_name']."</h4>
                                            <p>".$x_value['value']."</p>
                                        </div>";
                                }
                                ?>
                                
                            </div>
                            <?php } 
                            }
                            ?>
                            
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

<?php
    }
}    
else
{   

    if($amsprojectid == null)
    {
        echo "<p>Please add a Project ID to use the AMS Project Page Block. If you want to see a list of projects, use the AMS Projects Block.</p>";
    }
    else
    {
        echo "<div class='custom-model-main model-open'>
            <div class='custom-model-inner amsloginpopup'>        
              <div class='close-btn'>×</div>
                  <div class='custom-model-wrap'>
                      <span id='amscredentials_error'></span>
                      <div class='pop-up-content-wrap'>
                        <p>The project content is restricted by a password. Please enter the password to continue.</p>
                         <input type='hidden' id='getpageid' value=".get_the_ID().">
                         <input type='text' name='projectpassword' id='projectpassword'>
                         <input type='submit' id='projectsubmit' style='background-color: ".$bgcolor."'>
                          <span class='customprojectloader' id='projectinifiniteLoader'>
                            <img src=".esc_url( plugins_url( 'assets/img/buttonloader.gif', dirname(__FILE__) ) ).">
                          </span>";
                          
                            if($project_paymenturl)
                            {
                              if($project_paymentmessage)
                              {
                                echo "<p class='amsmargintop'>".$project_paymentmessage."</p>"; 
                              }
                              else
                              {
                                echo "<p class='amsmargintop'>If you don’t have a password, you can make a payment at this link to receive it.</p>"; 
                              }

                              if($paymentbuttonname)
                              {
                                $buttonname = $paymentbuttonname;
                              }
                              else
                              {
                                $buttonname = "Pay Here";
                              }
                              echo "<div class='paymentdiv'><a target='_blank' class='paymentclass' style='background-color: $bgcolor' href=".$project_paymenturl.">".$buttonname."</a></div>";
                            }
                          
                      echo "</div>
                  </div>  
              </div>  
            </div>  
            <div class='bg-overlay'></div>
        </div>"; 
    }
           

    /*$post->post_name;
    $URL=site_url($post->post_name);
    echo "<script type='text/javascript'>document.location.href='".$URL."';</script>";*/
}    
?>

</div>    

<script type="text/javascript">
jQuery(document).ready(function($) {

   var count = 2;
   var total = jQuery("#inifiniteLoader").data("totalequipment");
   var pageid = jQuery("#getpageid").val();
   jQuery('#projectinifiniteLoader').hide();

   var logintoken = "<?php echo $_SESSION['accesstoken']; ?>";    
    var amscredentials = "<?php echo $blocks[0]['attrs']['amscredentials']; ?>";
    var amssinglevideo = "<?php echo $amssinglevideonew; ?>";
    var audiofileAttachment = "<?php echo $audiofileAttachment; ?>";
    var audiofileAttachment = "<?php echo $audiofileAttachment; ?>";
    var amsprojectpagesidebar = "<?php echo $amsprojectpagesidebar; ?>";


    var projectpassword = "<?php echo $_GET['password']; ?>";
    var url = $(this).val();   

    if(amsprojectpagesidebar == 1)
    {    
        $(".video-main").css({"width": "100%"}); 
        $(".video-main img").css({"height": "auto"}); 
    }
      
    if(projectpassword)
    {    
          
          var getpageid = jQuery('#getpageid').val();

          var custbillingEmail = jQuery("#custbillingEmail").val();
        
          $.ajax({
             url: amsjs_ajax_url.ajaxurl,
             type:'POST',
             data: { action: 'get_amssingleprojectpagelog', projectpassword:projectpassword,getpageid:getpageid},
             success: function (data) {
                jQuery('.customprojectloader').hide('1000');
                jQuery("#projectsubmit").prop('disabled', false);

                var mydata = data.substring(0,data.length - 1);

                console.log(mydata);
             }
          });

          if(custbillingEmail == null)
          {
            location.reload();
          }
    }  

    function amsblocklogin()
    {
      var projectpasswordsession = "<?php echo $_SESSION['projectpassword']; ?>"; 
      var project_protected = "<?php echo $protectedpassword; ?>";
      if(projectpasswordsession == '' && project_protected != '')
      {
        $(document).on("click", ".projectdiv a", function() {
           var projectpageid = jQuery(this).data("pageid");
           var projectuserid = jQuery(this).data("userid");
            console.log(projectuserid);
            jQuery("input[type=hidden][name=projectidams]").val(projectpageid); 
            jQuery("input[type=hidden][name=projectuserams]").val(projectuserid); 
            jQuery(".custom-model-main").addClass('model-open');
        });

        jQuery(".close-btn, .bg-overlay").click(function(){
          jQuery(".custom-model-main").removeClass('model-open');
        });
      } 
    }

    $('#projectsubmit').click(function(){
        
        var projectpassword = jQuery('#projectpassword').val();
        var getpageid = jQuery('#getpageid').val();
        var projectidams = jQuery('#projectidams').val();
        var projectuserams = jQuery('#projectuserams').val();
        var mailsiteurl = "<?php echo site_url('/project/'); ?>";
        
        $.ajax({
             url: amsjs_ajax_url.ajaxurl,
             type:'POST',
             data: { action: 'get_amssingleprojectpagelog', projectpassword:projectpassword,getpageid:getpageid},
             beforeSend: function(){
                jQuery(".customprojectloader").show();
                jQuery("#projectsubmit").prop('disabled', true);
             },
             success: function (data) {
                jQuery('.customprojectloader').hide('1000');
                jQuery("#projectsubmit").prop('disabled', false);

                var mydata = data.substring(0,data.length - 1);

                if(mydata == 'valid')
                {
                    location.reload();
                }
                else
                {
                    jQuery("#amscredentials_error").html('<p>Access Credentials Denied.</p>');
                    jQuery("#amscredentials_error").css("color", "red");
                    jQuery("#amscredentials_error").css("display", "block");

                    setTimeout(function() {
                        $('#amscredentials_error').fadeOut('fast');
                    }, 5000);
                    
                }
            }
        });
    
    });


    if(amssinglevideo)
    {
        jwplayer("video_player").setup({
                image: "<?php echo $videoBanner; ?>",
                width: "100%",
                aspectratio: "12:7",
                autostart: "false",
                sources: [{
                    file: amssinglevideo
                }]
        });
    }
    else if(audiofileAttachment)
    {
        audioSetUp("amsaudio_player",audiofileAttachment);

        function audioSetUp(id, url){
         jwplayer(id).setup({
                    file: url,
                    height: 30,
             primary:"flash"
                });
        }
    }
   

    jQuery('[audiopopup-open]').on('click', function() {

        var audiourlid = jQuery(this).data("id");
        var audiourl = jQuery(this).data("audiourl");

        console.log(audiourl);

        console.log(audiourlid);

        audioSetUp("player"+audiourlid,audiourl);

        function audioSetUp(id, url){
         jwplayer(id).setup({
                    file: url,
                    width: 500,
                    height: 30,
             primary:"flash"
                });
        }
          
        var audiopopup = jQuery(this).attr('audiopopup-open');
        jQuery('[audiopopup-name="' + audiopopup + '"]').fadeIn(300);
          
    });
    

    jQuery('[popup-open]').on('click', function() {

        if(logintoken)
        {
            var videourl = jQuery(this).data("img");
            var videourlid = jQuery(this).data("id");

            var videonew = document.getElementById('amspopupvideo'+videourlid);

            jwplayer(videonew).setup({
                flashplayer: "player.swf",
                width: "100%",
                file: videourl
            });
            
            var popup_name = jQuery(this).attr('popup-open');
            jQuery('[popup-name="' + popup_name + '"]').fadeIn(300);
        }    
        else
        {
            alert("Please login to watch the video");
        }  
          
    });



    jQuery('[popup-close]').on('click', function() {
        var popup_name = jQuery(this).attr('popup-close');
        var videourlid = jQuery(this).data("id");
        jwplayer(videourlid).stop();
        jQuery('[popup-name="' + popup_name + '"]').fadeOut(300);
    });


    jQuery('[audiopopup-close]').on('click', function() {
        var popup_name = jQuery(this).attr('audiopopup-close');
        var audiourlid = jQuery(this).data("id");
        jwplayer(audiourlid).stop();
        jQuery('[audiopopup-name="' + popup_name + '"]').fadeOut(300);
    });



    jQuery('[imagepopup-open]').on('click', function() {

        var imageurlid = jQuery(this).data("id");
        var imageurl = jQuery(this).data("imageurl");

        document.getElementById('amsimage'+imageurlid).src = imageurl;
          
        var imagepopup = jQuery(this).attr('imagepopup-open');
        jQuery('[imagepopup-name="' + imagepopup + '"]').fadeIn(300);
          
    });

    jQuery('[imagepopup-close]').on('click', function() {
        var popup_name = jQuery(this).attr('imagepopup-close');
        var audiourlid = jQuery(this).data("id");
        jQuery('[imagepopup-name="' + popup_name + '"]').fadeOut(300);
    });
   
});    
</script>
  
<?php
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}

add_shortcode('amsprojectpage', 'amsprojectpage_function');

?>