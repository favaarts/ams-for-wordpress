<?php

get_header();  
session_start();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hls.js/0.5.14/hls.min.js"></script>
<script type="text/javascript" src="https://d2nvlqutlc7e9k.cloudfront.net/jwplayer.js"></script>
<script>jwplayer.key="KfEj20iL73YJlQBSm+6bqoSc148Cf5eSvIn2BWoo/Zg=";</script>
<div class="container-wrap">

<?php

global $wp, $wpdb;
$alleventid = $wp->query_vars['projectslug'];
$arrayevid = explode("-",$alleventid);

$arrayResult = get_projectdetails($arrayevid[0]);

$crewrole = "Crew%20Role";
$attributeCrewResult = get_projectattributes($arrayevid[0],$crewrole);

$photo = "Media%20Type";
$attributePhoto = get_projectattributes($arrayevid[0],$photo);

$longattributes = "Long%20Attributes";
$longAttribute = get_projectattributes($arrayevid[0],$longattributes);

$post = get_post($arrayevid[2]);
$blocks = parse_blocks($post->post_content);

$projectconnectmemberid = $wpdb->get_var('SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_content LIKE "%[members_list]%" AND post_parent = 0 AND post_status = "publish"');

// Connect to member
$connectmember = get_post($projectconnectmemberid);
$connectmemberblocks = parse_blocks($connectmember->post_content);

// Session
if(isset($_SESSION["projectpassword"]))
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
                                <!-- <video id="videobanner" poster="<?php //echo $videoBanner; ?>" controlsList="nodownload" width="517">
                                </video> -->
                            </div>

                            <div class="ing-title">
                                <?php
                                echo  "<h1> ". $arrayResult['project']['name'] ;
                                  if($arrayResult['project']['completed_year'])
                                  {
                                    echo " (".$arrayResult['project']['completed_year'].")";
                                  }
                                  echo "</h1>";
                                
                                if($arrayResult['project']['creator'])
                                {
                                    echo "<div class='enrollment enrtop'>
                                        <h3>Submitted By</h3>";
                                      if ($blocks[0]['attrs']['projecttomember'] && $connectmemberblocks[0]['blockName'] == "wpdams-amsnetwork-member/amsnetwork-block-member")  
                                      {
                                       
                                            echo "<p><a target='_blank' href='".site_url('/members/'.$arrayResult['project']['user_id'].'-'.$projectconnectmemberid.'/details' )."'>".$arrayResult['project']['creator']."</a></p>";
                                        
                                      }
                                      else
                                      {
                                        echo "<p>".$arrayResult['project']['creator']."</p>";
                                      }
                                        
                                    "</div>";
                                }

                                foreach($attributeCrewResult['project_attributes'] as $x_value) 
                                {
                                    if($x_value['project_attribute_type_name'] == "Director")
                                    {
                                        echo "<div class='enrollment'>
                                        <h3>Director(s)</h3>";
                                        if ($blocks[0]['attrs']['projecttomember'] && $connectmemberblocks[0]['blockName'] == "wpdams-amsnetwork-member/amsnetwork-block-member")  
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
                                        if ($blocks[0]['attrs']['projecttomember'] && $connectmemberblocks[0]['blockName'] == "wpdams-amsnetwork-member/amsnetwork-block-member")  
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
                                    
                                ?>
                            </div>
                                
                        </div>

                        <div class="project-detail-sec">
                            <div class="left-sec">
                                <?php
                                if($arrayResult['project']['synopsis'])
                                {
                                    echo "<div class='synopsis prospace'>";
                                    //echo "<h3>Synopsis:</h3>";
                                        echo "<div class='text-sec'>";
                                            echo "<p>".$arrayResult['project']['synopsis']."</p>";
                                        echo "</div>";
                                    echo "</div>";
                                }
                                ?>
                                 

                                <?php
                               

                                   
                                if(count($attributePhoto['project_attributes']) > 1)
                                {
                                ?> 
                                <div class="videos prospace amsvideos">
                                   
                                    <div class="video-row">
                                    <h3>Media:</h3>    
                                        <?php
                                        $i = 1;
                                        
                                        array_shift($attributePhoto['project_attributes']);

                                        if(isset($attributePhoto['project_attributes']))
                                        {    
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
                                        }    
                                        ?>

                                    </div>
                                </div>
                                <?php }  ?>
                                

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

                            ?>

                            </div>
                            

                            <?php
                            $shortattribute = "Short%20Attributes";
                            $shortAttributeResult = get_projectattributes($arrayevid[0],$shortattribute);


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
                            <?php } ?>
                            
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
    $post->post_name;
    $URL=site_url($post->post_name);
    echo "<script type='text/javascript'>document.location.href='".$URL."';</script>";
}    
    ?>
</div>    

<script type="text/javascript">
jQuery( document ).ready(function() {

    var logintoken = "<?php echo $_SESSION["accesstoken"]; ?>";    
    var amscredentials = "<?php echo $blocks[0]['attrs']['amscredentials']; ?>";
    var amssinglevideo = "<?php echo $amssinglevideonew; ?>";
    var audiofileAttachment = "<?php echo $audiofileAttachment; ?>";
    console.log(audiofileAttachment);
    /**/
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
    /*jQuery("#videobanner").click(function() {
        if(logintoken || amscredentials == '')
        {
            if(amssinglevideo != '')
            {
                if(Hls.isSupported()) {
                var hls = new Hls();
                hls.loadSource(amssinglevideo);
                hls.attachMedia(this);
                document.getElementById("videobanner").controls = true;
                jQuery(".video-icon").hide();
                jQuery(this).get(0).play();
                }
            }
            else
            {
                alert("No video found");
                jQuery(this).show();
            }

              
        }
        else
        {
            alert("Please login to watch the video");
        }
    });*/

    
    // Open Popup
    jQuery('[popup-open]').on('click', function() {

        if(logintoken || amscredentials == '')
        {
            var videourl = jQuery(this).data("img");
            var videourlid = jQuery(this).data("id");

            var videonew = document.getElementById('amspopupvideo'+videourlid);

            jwplayer(videonew).setup({
                flashplayer: "player.swf",
                width: "100%",
                file: videourl
            });
            /*jwplayer("amspopupvideo"+videonew).setup({
                  width: "100%",
                  aspectratio: "12:7",
                  autostart: "false",
                  sources: [{
                    file: videourl
                  }]
            });*/

            /*if(Hls.isSupported()) {
                var hls = new Hls();
                hls.loadSource(videourl);
                hls.attachMedia(videonew);
            } */

            var popup_name = jQuery(this).attr('popup-open');
            jQuery('[popup-name="' + popup_name + '"]').fadeIn(300);
        }    
        else
        {
            alert("Please login to watch the video");
        }  
          
    });

    /* ==== MP3 audio ====*/
    jQuery('[audiopopup-open]').on('click', function() {

        var audiourlid = jQuery(this).data("id");
        var audiourl = jQuery(this).data("audiourl");

        console.log(audiourl);

        console.log(audiourlid);

         audioSetUp("player"+audiourlid,audiourl);

        function audioSetUp(id, url){
         jwplayer(id).setup({
                    file: url,
                    height: 30,
             primary:"flash"
                });
        }
          
        var audiopopup = jQuery(this).attr('audiopopup-open');
        jQuery('[audiopopup-name="' + audiopopup + '"]').fadeIn(300);
          
    });
    /* ==== End MP3 audio ====*/


    // Close Popup
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

    // Image popup
    jQuery('[imagepopup-open]').on('click', function() {

        var imageurlid = jQuery(this).data("id");
        var imageurl = jQuery(this).data("imageurl");

        console.log(imageurl);

        console.log(imageurlid);

        document.getElementById('amsimage'+imageurlid).src = imageurl;
          
        var imagepopup = jQuery(this).attr('imagepopup-open');
        jQuery('[imagepopup-name="' + imagepopup + '"]').fadeIn(300);
          
    });

    jQuery('[imagepopup-close]').on('click', function() {
        var popup_name = jQuery(this).attr('imagepopup-close');
        var audiourlid = jQuery(this).data("id");
        jQuery('[imagepopup-name="' + popup_name + '"]').fadeOut(300);
    });
    // End image popup


});        
</script>

<?php
get_footer();
