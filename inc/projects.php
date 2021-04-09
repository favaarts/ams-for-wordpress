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

$blockdata = get_sidebaroption();

$gridlayout = $blockdata['radio_attr_project'];

if($blockdata['project_pagination'] != NULL)
{
  $pagination = $blockdata['project_pagination'];
}
else
{
  $pagination = 8;
}

if($gridlayout == "four_col")
{
   $blockclass = 'main-content-four-col';
}
elseif($gridlayout == "two_col")
{
  $blockclass = '';
}
else
{
   $blockclass = 'main-content-three-col';
}


?>

<div class="wp-block-columns main-content <?= $blockclass; ?>" >

  <?php

  if (!isset($blockdata['projectsidebar']))
  {        
  ?>
    <div class="wp-block-column left-col col-fit" >
        
        <div class="assetssidebar">
            <div class="searchbox">
                <h4>Search</h4>
                <input type="text" class="searrch-input" name="keyword" id="getproject" onkeyup="fetchproject()"></input>
            </div>
        </div>    
        
    </div>  
  <?php } ?> 
   
    <!-- Projects -->
    <div class="projectslisting right-col" >       
      <div class="right-col-wrap">
      <input type="hidden" id="getpageid" value="<?php echo get_the_ID(); ?>">   
      <?php

      $arrayResult = get_projectlisting(NULL);
      $bgcolor = get_option('wpams_button_colour_btn_label');
      if(empty($bgcolor))
      {
          $bgcolor = "#337AB7";
      }

      if($blockdata['radio_attr_project'] == "list_view")
      {
        foreach($arrayResult['projects'] as $x_value) 
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
          if($_SESSION["projectpassword"] || $blockdata['project_protected'] == NULL)
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
            echo  "<p class='product-title'> ". $x_value['name'] ;
            if($x_value['completed_year'])
            {
              echo " (".$x_value['completed_year'].")";
            }
            echo "</p>";
          }  

          echo "<div class='assetsprice'>";
          echo    "<p class='memberprice'><strong>Created By</strong> - ". $x_value['creator']. "</p>";

          if($synopsis != NULL)
          {
          echo "<p class='price-non-mem'><strong>Synopsis</strong> - ". $synopsis ."</p>";
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
        }
      }
      else
      {
        foreach($arrayResult['projects'] as $x_value) 
        {
          echo"<div class='productstyle projectdiv'>";
               
                if($_SESSION["projectpassword"] || $blockdata['project_protected'] == NULL)
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
                  echo  "<p class='product-title'>".$x_value['name'];
                  if($x_value['completed_year'])
                  {
                    echo " (".$x_value['completed_year'].")";
                  }
                  echo "</p>";
                  
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

      ?>  
           
      </div> 

      <div class="projectbutton">
            
            <p class="para"></p>
            <a id="inifiniteLoader"  data-totalequipment="<?php echo $arrayResult['meta']['total_count']; ?>" ><img src="<?php echo esc_url( plugins_url( 'assets/img/loader.svg', dirname(__FILE__) ) ) ?>" ></a>   

            <?php
            
            echo "<input type='button' id='seemore' style='background-color: $bgcolor' value='View More'>";
            
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
               <input type="submit" id="projectsubmit">
                <span class="customprojectloader" id="projectinifiniteLoader">
                  <img src="<?php echo esc_url( plugins_url( 'assets/img/buttonloader.gif', dirname(__FILE__) ) ) ?>">
                </span>
                <?php
                  if($blockdata['project_paymenturl'])
                  {
                    if($blockdata['project_paymentmessage'])
                    {
                      echo "<p>".$blockdata['project_paymentmessage']."</p>"; 
                    }
                    else
                    {
                      echo "<p>If you don’t have a password, you can make a payment at this link to receive it.</p>"; 
                    }

                    if($blockdata['paymentbuttonname'])
                    {
                      $buttonname = $blockdata['paymentbuttonname'];
                    }
                    else
                    {
                      $buttonname = "Pay Here";
                    }
                    echo "<div class='paymentdiv'><a target='_blank' class='paymentclass' href=".$blockdata['project_paymenturl'].">".$buttonname."</a></div>";
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
     
    var projectperpg = <?php echo $pagination; ?>;
      
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
      var project_protected = "<?php echo $blockdata['project_protected']; ?>";
      if(projectpasswordsession == '' && project_protected != '')
      {
        jQuery(".projectdiv").on('click', function() {
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
                    location.reload();
                 }
                 else
                 {
                    jQuery("#amscredentials_error").html('<p>AMS Credentials not match.</p>');
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
