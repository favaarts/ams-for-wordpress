<?php
session_start();
function amsprojects_function( $slug ) {
    ob_start();  
    ?>

<div id="category" class="category cat-wrap">

  
<div class="entry-content main-content-wrap">
 
<!-- ======================================================================
notes::
main-content - this class is for two columns.
main-content main-content-three-col - this class is for three columns.
main-content main-content-four-col - this class is for four columns.
======================================================================  -->
<?php

global $post;
$pageid = $post->ID;
$post_slugpage = $post->post_name;

$reels_id = false;
if(isset($_GET['reels_id'])){
    $reels_id = $_GET['reels_id'];
}

$blockdata = get_sidebaroption();

// Get AMS BLOCK
$post_id = get_the_ID();
$post = get_post($post_id);
$amsblocksetting = parse_blocks($post->post_content);

foreach($amsblocksetting as $amsblock) 
{
    if($amsblock['blockName'] == "wpdams-amsnetwork-project/amsnetwork-block-project")
    { 
        $protectedpassword = $amsblock['attrs']['project_protected'];
        $pagination = $amsblock['attrs']['project_pagination'];
        $amsprojectid = $amsblock['attrs']['amsprojectid'];
        $radioattrproject = $amsblock['attrs']['radio_attr_project'];
        $firstpartmailtext = $amsblock['attrs']['firstpartmailtext'];
        $secondpartmailtext = $amsblock['attrs']['secondpartmailtext'];
        $projectsidebar = $amsblock['attrs']['projectsidebar'];
        $amsreelid = $amsblock['attrs']['amsreelid'];
        $remove_viewmore = $amsblock['attrs']['remove_viewmore'];
        $project_paymenturl = $amsblock['attrs']['project_paymenturl'];
        $project_paymentmessage = $amsblock['attrs']['project_paymentmessage'];
        $paymentbuttonname = $amsblock['attrs']['paymentbuttonname'];
        
    }
}  
// End AMS BLOCK Setting


if($amsprojectid)
{
  $getamsprojectid = get_projectdetails($amsprojectid);
  $URL=site_url('/project/'.$amsprojectid.'-'.$getamsprojectid['project']['user_id'].'-'.$pageid);
  echo "<script type='text/javascript'>document.location.href='".$URL."';</script>";
}

$gridlayout = $radioattrproject;

if($pagination != NULL)
{
  $pagination = $pagination;
}
else
{
  $pagination = 8;
}

if($gridlayout == "four_col")
{
   $blockclass = 'main-content-four-col';
   $projectperpage = $pagination;
}
elseif($gridlayout == "two_col")
{
  $blockclass = '';
  $projectperpage = $pagination;
}
else
{
   $blockclass = 'main-content-three-col';
   $projectperpage = $pagination;
}

$nowtime = time();


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
?>

<div class="wp-block-columns main-content <?= $blockclass; ?>" >

  <?php

  //echo $_POST['billingEmailAddress'];
    $billingEmailAddress = $_POST['billingEmailAddress'];
    $loginPageURL=site_url($post_slugpage);
    if($_SESSION['billingEmailAddress'] = $billingEmailAddress)  
    {
      $_SESSION['billingContactName'] = $_POST['billingContactName'];
      echo "<input type='hidden' id='custbillingEmail' name='custbillingEmail' value='".$_SESSION['billingEmailAddress']."'>";
      echo "<div class='amsuserlayout'>";
          echo "<div class='userlogin'><p>Hii, ".$_SESSION['billingEmailAddress']."</p></div>";
      
         get_sentmailproject($pageid,$_SESSION['billingEmailAddress'],$_GET['password'],$_SESSION['billingContactName'],$loginPageURL);     
         /* echo "<div class='amslogoutbutton'>
                  <input type='submit' id='btnAMSLogout' onclick='btnAMSLogout()' value='Log Out'>
                </div>";*/
                
      echo "</div>";  

      /* Popup */
      echo "<div class='custom-model-main model-open'>
                <div class='custom-model-inner amsloginpopup'>        
                <div class='close-btn'>×</div>
                    <div class='custom-model-wrap'>
                        <span id='amscredentials_error'></span>
                        <div class='pop-up-content-wrap'>
                           <h5>Hi ".$_SESSION['billingContactName']."</h2>
                          <p>".$firstpartmailtext."</p>
                          <p><strong>URL:</strong> ".$loginPageURL."</p>
                          <p><strong>Password:</strong> ".$_GET['password']."</p>
                          <p>".$secondpartmailtext."</p>
                          <div class='continuetag'>
                          <a class='paymentclass' style='background-color: $bgcolor' href='".$loginPageURL."'>Continue</a>
                          </div>
                        </div>
                    </div>  
                </div>  
            </div>";         
      /* End Popup */                         
      
    }

  // Remove Project sidebar
  if (!isset($projectsidebar))
  {        
  ?>
    <div class="wp-block-column left-col col-fit" >
        
        <div class="assetssidebar">

            

            <div class="searchbox">
                <h4>Search</h4>
                <input type="text" class="searrch-input" name="keyword" id="getproject" data-protectedid="<?php echo $protectedpassword; ?>" onkeyup="fetchproject()"></input>
            </div>

            <?php
              if(empty($amsreelid))
              {
                echo "<ul class='ul-cat-wrap getcategoryid'>";
                echo "<li><a href='".site_url('/project')."'>All Projects</a></li>";
                $reelsArrayResult = get_getallReels();

                foreach($reelsArrayResult['reels'] as $c => $c_value) 
                {
                  echo "<li><a href='".site_url('/project/?reels_id='. $c_value['id'])."'>".$c_value['name']."</a></li>"; 
                }
                echo "</ul>";
              }
            ?>
        </div>    
        
    </div>  
  <?php } ?> 
   
    <!-- Projects -->
    <div class="projectslisting right-col" >       
      <div class="right-col-wrap">
      <input type="hidden" id="getpageid" value="<?php echo get_the_ID(); ?>">   
      <?php

      $arrayResult = get_projectlisting(NULL,$reels_id);
      

      if($radioattrproject == "list_view")
      {
        
          foreach($arrayResult['projects'] as $x_value) 
          {
            
            if($x_value['can_view_projects'] == 'true')
            {
              $synopsis = mb_strimwidth($x_value['synopsis'], 0, 150, '...');
                
              echo "<div class='listview-project projectdiv'>";
              echo "<div class='assets-list-items'>";
              

              if($x_value['thumbnail'] == NULL || $x_value['thumbnail'] == "")
                    {                                    
                        echo "<div class='product-img'>";
                         echo "<div class='productthumb'>";
                        echo "<img src=". esc_url( plugins_url( 'assets/img/bg-image.png', dirname(__FILE__) ) ) .">";
                          echo "</div>";
                        echo "</div>";
                    }
                    else
                    {
                         echo "<div class='product-img'>";
                           echo "<div class='productthumb'>";
                            echo "<img src=".$x_value['thumbnail'].">";
                          echo "</div>";  
                         echo "</div>";
                    }

              
              echo "<div class='assetsproduct-content'>";
              if(isset($_SESSION["projectpassword"]))
              {
                if($nowtime > $_SESSION['expire'])
                {
                  session_unset();
                  session_destroy();
                }  
                else
                {
                  echo "<a href='".site_url('/project/'.$x_value['id'].'-'.$x_value['user_id'].'-'.$pageid)."'>";
                  echo  "<p class='product-title'> ". $x_value['name'] ;
                  if($x_value['completed_year'])
                  {
                    echo " (".$x_value['completed_year'].")";
                  }
                  echo "</p>";
                  echo  "</a>";
                }  
              }
              elseif($protectedpassword == NULL)
              {
                  echo "<a href='".site_url('/project/'.$x_value['id'].'-'.$x_value['user_id'].'-'.$pageid)."'>";
                  echo  "<p class='product-title'> ". $x_value['name'] ;
                  if($x_value['completed_year'])
                  {
                    echo " (".$x_value['completed_year'].")";
                  }
                  echo "</p>";
                  echo  "</a>";
              }
              else
              {
                echo "<a href='javascript:void(0)' data-pageid='".$x_value['id']."' data-userid='".$x_value['user_id']."'>";
                echo  "<p class='product-title'> ". $x_value['name'] ;
                if($x_value['completed_year'])
                {
                  echo " (".$x_value['completed_year'].")";
                }
                echo "</p>";
                echo  "</a>";
              }  

              echo "<div class='assetsprice'>";
              echo    "<p class='memberprice'><strong>Created By</strong> - ". $x_value['creator']. "</p>";

              if($synopsis != NULL)
              {
              echo "<p class='price-non-mem'>". $synopsis ."</p>";
              }
              else
              {
                $attributeResult = get_projectattributes($x_value['id']);
                if($attributeResult['project_attributes'][0]['value'] != NULL)
                {

                echo "<p class='price-non-mem'><strong>".$attributeResult['project_attributes'][0]['project_attribute_type_name']."</strong> - ". $attributeResult['project_attributes'][0]['value'] ."</p>";
                }

              }
              echo "</div>";
              echo "</div>";
              echo "</div>";
              echo "</div>";  
            //
            }
          }  
      }
      else
      {
        foreach($arrayResult['projects'] as $x_value) 
        {

          if($x_value['can_view_projects'] == 'true')
          {
            echo"<div class='productstyle projectdiv'>";
                 
                    if(isset($_SESSION["projectpassword"]))
                    {
                      if($nowtime > $_SESSION['expire'])
                      {
                        session_unset();
                        session_destroy();
                      }  
                      else
                      {
                        echo "<a href='".site_url('/project/'.$x_value['id'].'-'.$x_value['user_id'].'-'.$pageid)."'>";
                        echo  "<p class='product-title'>".$x_value['name'];
                        if($x_value['completed_year'])
                        {
                          echo " (".$x_value['completed_year'].")";
                        }
                        echo "</p>";
                        echo "</a>";
                      }    

                  }
                  elseif($protectedpassword == NULL)
                  {
                      echo "<a href='".site_url('/project/'.$x_value['id'].'-'.$x_value['user_id'].'-'.$pageid)."'>";
                      echo  "<p class='product-title'>".$x_value['name'];
                      if($x_value['completed_year'])
                      {
                        echo " (".$x_value['completed_year'].")";
                      }
                      echo "</p>";
                      echo "</a>";
                  } 
                  else
                  {   
                    echo "<a href='javascript:void(0)' data-pageid='".$x_value['id']."' data-userid='".$x_value['user_id']."'>";
                    echo  "<p class='product-title'>".$x_value['name'];
                    if($x_value['completed_year'])
                    {
                      echo " (".$x_value['completed_year'].")";
                    }
                    echo "</p>";
                    echo "</a>";
                    
                  } 

                  if($x_value['thumbnail'] == NULL || $x_value['thumbnail'] == "")
                  {                                    
                      echo "<div class='product-img-wrap'>";
                        echo "<img src=". esc_url( plugins_url( 'assets/img/bg-image.png', dirname(__FILE__) ) ) .">";
                      echo "</div>";
                  }
                  else
                  {
                       echo "<div class='product-img-wrap'>";
                          echo "<img src=".$x_value['thumbnail'].">";
                       echo "</div>";
                  }
                  echo "<p class='memberprice'><strong>Created By</strong> - ". $x_value['creator']. "</p>";
            echo"</div>";

            }
        }
          
      }  

      ?>  
           
      </div> 

      <div class="projectbutton">
            
            <p class="para"></p>
            <a id="inifiniteLoader"  data-totalequipment="<?php echo $arrayResult['meta']['total_count']; ?>" ><img src="<?php echo esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) ?>" ></a>   

            <?php
            if (isset($remove_viewmore))
            { 
              echo "<input type='button' id='seemore' style='background-color: $bgcolor' value='View More'>";
            }
            ?>  
        </div>   

  </div> 
    <!-- End projects -->

</div>
   
</div>

<!-- Popup -->
<div class="custom-model-main">
    <div class="custom-model-inner amsloginpopup">        
    <div class="close-btn">×</div>
        <div class="custom-model-wrap">
            <span id="amscredentials_error"></span>
            <div class="pop-up-content-wrap">
              <p>The project content is restricted by a password. Please enter the password to continue.</p>
               <input type="text" name="projectpassword" id="projectpassword">
               <input type="hidden" name="projectidams" id="projectidams">
               <input type="hidden" name="projectuserams" id="projectuserams">
               <input type="submit" id="projectsubmit" style="background-color: <?php echo $bgcolor ?>">
                <span class="customprojectloader" id="projectinifiniteLoader">
                  <img src="<?php echo esc_url( plugins_url( 'assets/img/buttonloader.gif', dirname(__FILE__) ) ) ?>">
                </span>
                <?php
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
                ?>
            </div>
        </div>  
    </div>  
    <div class="bg-overlay"></div>
</div>
<!-- End popup -->

<script type="text/javascript">
jQuery(document).ready(function($) {

   var count = 2;
   var total = jQuery("#inifiniteLoader").data("totalequipment");
   var pageid = jQuery("#getpageid").val();

    /*Password URL Redirect*/
    var projectpassword = "<?php echo $_GET['password']; ?>";
    var url = $(this).val();    
      
    if(projectpassword)
    {    
          
          var getpageid = jQuery('#getpageid').val();

          var custbillingEmail = jQuery("#custbillingEmail").val();
        
          $.ajax({
             url: amsjs_ajax_url.ajaxurl,
             type:'POST',
             data: { action: 'get_amsprojectlog', projectpassword:projectpassword,getpageid:getpageid},
             success: function (data) {
               /* console.log(data);*/
                jQuery('.customprojectloader').hide('1000');
                jQuery("#projectsubmit").prop('disabled', false);

                var mydata = data.substring(0,data.length - 1);

                console.log(mydata);
             }
          });

          if(custbillingEmail == null)
          {
            window.location.replace("<?php echo $loginPageURL; ?>");
          }
    }  
   /*End password URL redirect*/

   $('#inifiniteLoader').hide();
   jQuery(".customprojectloader").hide();
   amsblocklogin();

    $('#seemore').click(function(){

     /* var position = $(window).scrollTop();
      var bottom = $(document).height() - $(window).height();*/
        //$('#seemore').hide();
        
        var numItems = jQuery('.productstyle').length;
        var listnumItems = jQuery('.listview-project').length;   
        
        var totalItems = "";
        if(numItems != '')  
        {
          totalItems = numItems;
        }
        else
        {
          totalItems = listnumItems;
        }

        console.log(totalItems);
        console.log(total);


        if (totalItems >= total){
            jQuery('#seemore').hide();
            jQuery(".para").text("No More Projects found.");  
        }else{
            jQuery('#seemore').hide();   
            loadArticle(count);
        }
        count++;
        

        return false;
    });


    function loadArticle(pageNumber){
     
    var projectperpg = <?php echo $projectperpage; ?>;
      
     $.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       data: { action: 'getprojectonclick_action', page:pageNumber, pageid:pageid, projectperpg: projectperpg},
       beforeSend: function(){
        // Show image container
            $("#inifiniteLoader").show();
       },
       success: function (html) {
         jQuery('#inifiniteLoader').hide();
         jQuery('.right-col-wrap').append(html);
         jQuery('#seemore').show();
         amsblocklogin();
       }
     });
     return false;
    }

    // Custom popup login
    function amsblocklogin()
    {
      var projectpasswordsession = "<?php echo $_SESSION["projectpassword"]; ?>"; 
      var project_protected = "<?php echo $protectedpassword; ?>";
      if(projectpasswordsession == '' && project_protected != '')
      {
        /*jQuery(".projectdiv").on('click', function() {
          jQuery(".custom-model-main").addClass('model-open');
        }); */

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
             data: { action: 'get_amsprojectlog', projectpassword:projectpassword,getpageid:getpageid},
             beforeSend: function(){
              // Show image container
                jQuery(".customprojectloader").show();
                jQuery("#projectsubmit").prop('disabled', true);
             },
             success: function (data) {
               /* console.log(data);*/
                jQuery('.customprojectloader').hide('1000');
                jQuery("#projectsubmit").prop('disabled', false);

                var mydata = data.substring(0,data.length - 1);

                console.log(mydata);

                 if(mydata == 'valid')
                 {
                    var siteurl = mailsiteurl+projectidams+'-'+projectuserams+'-'+getpageid;
                    window.location.href = siteurl;
                    //location.reload();
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
   
});    
</script>

  
<?php
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}

add_shortcode('amsproject', 'amsprojects_function');

?>
