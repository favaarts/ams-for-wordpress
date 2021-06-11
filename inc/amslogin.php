<?php
session_start();
function amslogin_function( $slug ) {
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


<div class="wp-block-columns main-content <?= $blockclass; ?>" >
   
    <!-- Projects -->
    <div class="projectslisting right-col" >       
      <div class="right-col-wrap">
        
        <div class="amsloginform">
            <div class="post-form-main">
               <?php
                
              if(isset($_SESSION["username"]))  
              {
                echo '<p>Hii, ' . $_SESSION["username"] . '</p>';
                
                echo '<p>'.$_SESSION["accesstoken"]. '</p>';
                
                echo "<input type='hidden' id='getaccesstoken' value='".$_SESSION["accesstoken"]."' />";
                echo "<input type='submit' id='btnAMSLogout' onclick='btnAMSLogout()' value='Log Out' />";
                echo "<div class='post-group customloader' id='inifiniteLoader' style='text-align: center;'>
                    <img src=".esc_url( plugins_url( 'assets/img/buttonloader.gif', dirname(__FILE__) ) )." >
                  </div>"; 
              }  
              else
              {  
                ?>
              
                   
                    <div class="post-group">
                        <span id="amscredentials_error"></span>
                        <input type="text" id="amsemailoruser" placeholder="Username" required>
                    </div>
                    <div class="post-group">
                        <input type="password" id="amspassword" name="password" placeholder="Password" required>
                    </div>
                
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="LogIn" />
                    
                    <div class="post-group customloader" id="inifiniteLoader" style="text-align: center;">
                      <img src="<?php echo esc_url( plugins_url( 'assets/img/buttonloader.gif', dirname(__FILE__) ) ) ?>" >
                    </div>
             
              <?php } ?>      
            </div>

        </div>   

      </div> 


  </div> 
    <!-- End projects -->

</div>
   
</div>



<script type="text/javascript">
jQuery(document).ready(function($) {
    
    jQuery("#inifiniteLoader").hide(); 
    
    $('#btnSubmit').click(function(){

        var amsemailoruser = jQuery('#amsemailoruser').val();
        var amspassword = jQuery('#amspassword').val();   
        
          $.ajax({
             url: amsjs_ajax_url.ajaxurl,
             type:'POST',
             data: { action: 'get_amsmemberlogindetails', amsemailoruser:amsemailoruser, amspassword:amspassword},
             beforeSend: function(){
              // Show image container
                  jQuery("#inifiniteLoader").show();
                  jQuery("#btnSubmit").attr("disabled", true);
             },
             success: function (data) {
                jQuery('#inifiniteLoader').hide();
                jQuery("#btnSubmit").attr("disabled", false);
                var mydata = data.substring(0,data.length - 1);

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

add_shortcode('amslogin', 'amslogin_function');

?>
