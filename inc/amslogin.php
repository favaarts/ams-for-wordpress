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
              }  
              else
              {  
                ?>
              
                   
                    <div class="post-group">
                        <input type="text" id="amsemailoruser" placeholder="Username" required>
                    </div>
                    <div class="post-group">
                        <input type="password" id="amspassword" name="password" placeholder="Password" required>
                    </div>
                
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Save Changes" />
               <!--  <div class="post-group">
                    <button type="submit" id="protectedamsvideo" class="btn btn-primary btn-block btn-large">Let me in 26.</button>
                </div> -->
             
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
   
    $('#btnSubmit').click(function(){

        var amsemailoruser = jQuery('#amsemailoruser').val();
        var amspassword = jQuery('#amspassword').val();   
        
          $.ajax({
             url: amsjs_ajax_url.ajaxurl,
             type:'POST',
             data: { action: 'get_amsmemberlogindetails', amsemailoruser:amsemailoruser, amspassword:amspassword},
             beforeSend: function(){
              // Show image container
                  $("#inifiniteLoader").show();
             },
             success: function (data) {

                var mydata = data.substring(0,data.length - 1);

                 if(mydata == 'valid')
                 {
                    location.reload();
                 }
                 else
                 {
                    console.log('Error data');
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
