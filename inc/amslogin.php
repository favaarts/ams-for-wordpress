<?php
session_start();
function amslogin_function( $slug ) {
    ob_start();  
    $usersData = get_amsmemberlogindata($_SESSION['accesstoken'], $_SESSION['user_id']);
    $subdomain = get_option('wpams_url_btn_label');
    if(isset($usersData) && !empty($usersData)):
        $logindata = $usersData['user'];
        $organization_id = isset($logindata) ? $logindata['organization_id'] : 0;
        $account_id = isset($logindata) ? $logindata['account_id'] : 0;
        $filter = 'current';
        $program_id = '8247';
        $programData = get_amsprogramdata($_SESSION['accesstoken'], $program_id);
        $programUsers = isset($programData['users']) ? $programData['users'] : [];
    else:
        $logindata = [];
    endif;
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
  
                echo '<h3>Access Token </h3><p>'.$_SESSION["accesstoken"]. '</p>';
              ?>
               <a class="text-info" href="javascript:void(0);" onclick="redirectAMSWithKey()">AMS Link</a>
                <div class="container">
                  <div class="row">
                    <div class="col-8">
                      <div class="card">
                        <div class="card-body p-0">
                          <div class="card-text p-4 pb-5">
                              <div class="mb-2 contact-info">
                                <div class="p-3 font-weight-bold" style="width: 100%; background-color: rgb(245, 239, 239);"><h4><center>Name & Organization Information</center></h4></div>
                                 <div class="row">
                                  <div class="col-12">
                                    <div class="row mt-3">
                                      <?php if(!empty($logindata['first_name'])): ?>
                                      <div class="col-md-6">
                                        <p>First Name</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="first_name" placeholder="" value="<?php echo $logindata['first_name']; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                      <?php if(!empty($logindata['last_name'])): ?>
                                      <div class="col-md-6">
                                        <p>Last Name</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="last_name" placeholder="" value="<?php echo $logindata['last_name']; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                    </div>
                                    <div class="row mt-3">
                                      <?php if(!empty($logindata['organization_name'])): ?>
                                      <div class="col-md-4">
                                        <p>Organization Member</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="organization_name" placeholder="" value="<?php echo $logindata['organization_name']; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                      <?php if(!empty($logindata['created_at'])): ?>
                                      <div class="col-md-4">
                                        <p>Membership Created</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="created_at" placeholder="" value="<?php echo date("d-m-Y", strtotime($logindata['created_at'])); ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                      <?php if(!empty($logindata['membership_expires'])): ?>
                                      <div class="col-md-4">
                                        <p>Membership Expires</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="membership_expires" placeholder="" value="<?php echo date("d-m-Y", strtotime($logindata['membership_expires'])); ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                    </div>
                                    <div class="row mt-3">
                                      <?php if(!empty($logindata['membership_dues'])): ?>
                                      <div class="col-md-6">
                                        <p>Membership Dues</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="membership_dues" placeholder="" value="<?php echo $logindata['membership_dues']; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                      <?php if(!empty($logindata['user_entity_type'])): ?>
                                      <div class="col-md-6">
                                        <p>User Entity Code</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="user_entity_type" placeholder="" value="<?php echo $logindata['user_entity_type']; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                    </div>
                                    <div class="row mt-3">
                                      <?php if(!empty($logindata['volunteer_hours_required_per_year'])): ?>
                                      <div class="col-md-6">
                                        <p>Volunteer Hours</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="volunteer_hours_required_per_year" placeholder="" value="<?php echo $logindata['volunteer_hours_required_per_year']; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                      <?php if(!empty($logindata['can_rent_equipment'])): ?>
                                      <div class="col-md-6">
                                        <p>Rent Equipment</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="can_rent_equipment" placeholder="" value="<?php if($logindata['can_rent_equipment'] == 1): echo "Active"; endif; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                    </div>
                                    <div class="row mt-3">
                                      <?php if(!empty($logindata['can_book_facilities'])): ?>
                                      <div class="col-md-6"> 
                                        <p>Book Facilities</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="can_book_facilities" placeholder="" value="<?php if($logindata['can_book_facilities'] == 1): echo "Active"; endif; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                      <?php if(!empty($logindata['can_register_in_programs'])): ?>
                                      <div class="col-md-6">
                                        <p>Register Programs</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="can_register_in_programs" placeholder="" value="<?php if($logindata['can_register_in_programs'] == 1): echo "Active"; endif; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                    </div>
                                    <div class="row mt-3">
                                      <?php if(!empty($logindata['gets_insurance_coverage'])): ?>
                                      <div class="col-md-3">
                                        <p>Insurance Coverage</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="gets_insurance_coverage" placeholder="" value="<?php if($logindata['gets_insurance_coverage'] == 1): echo "Active"; endif; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                      <?php if(!empty($logindata['jury_member'])): ?>
                                      <div class="col-md-3">
                                        <p>Jury Member</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="jury_member" placeholder="" value="<?php if($logindata['jury_member'] == 1): echo "Active"; endif; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                      <?php if(!empty($logindata['role'])): ?>
                                      <div class="col-md-3">
                                        <p>Role Name</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="role" placeholder="" value="<?php echo $logindata['role']['name']; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                       <?php if(!empty($logindata['member_type_name'])): ?>
                                      <div class="col-md-3">
                                        <p>Member Type Name</p>
                                        <div><div><div><div class=""></div>
                                        <div class="form-group">
                                           <input class="form-control undefined" name="member_type_name" placeholder="" value="<?php echo $logindata['member_type_name']; ?>" readonly style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                         </div></div></div></div>
                                      </div>
                                      <?php endif; ?>
                                    </div>
                                  </div>
                                </div>
                              </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br/><br/>
                  <div class="row">
                    <div class="col-8">
                      <div class="card">
                        <div class="card-body p-0">
                          <div class="card-text p-4 pb-5">
                            <form id="userUpdate" method="post" class="ajax" enctype="multipart/form-data" autocomplete="off">
                              <div class="mb-2 contact-info">
                                <div class="p-3 font-weight-bold" style="width: 100%; background-color: rgb(245, 239, 239);"><h4><center>Contact Information</center></h4></div>
                                <div class="row">
                                  <div class="col-12">
                                    <div class="row mt-3">
                                      <div class="col-md-5"><p>Email</p><div><div><div><div class=""></div>
                                      <div class="form-group">
                                         <input class="form-control undefined" name="email" id="email" placeholder="" value="<?php echo $logindata['email']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                      </div></div></div></div></div>
                                      <div class="col-md-3"><p>Pronouns </p><div><div><div><div class=""></div><div class="form-group">
                                          <input class="form-control undefined" name="pronouns" placeholder="" value="<?php echo $logindata['pronouns']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"></div></div></div></div>
                                      </div>
                                      <div class="col-md-4"><p>Job Title</p><div><div><div><div class=""></div>
                                        <div class="form-group">
                                          <input class="form-control undefined" name="job_position" placeholder="" value="<?php echo $logindata['job_position']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                        </div></div></div></div>
                                      </div>
                                      </div>
                                      <div class="row"></div><div class="row">
                                        <div class="col-md-12"><p>Bio</p><div><div><div><div class=""></div>
                                          <div class="form-group">
                                            <textarea class="form-control undefined" name="bio_link" placeholder="" cols="40" rows="3" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"><?php echo $logindata['bio_link']; ?></textarea>
                                          </div></div></div></div>
                                        </div>
                                        <div class="col-md-6"><p>Specializations </p><div><div><div><div class=""></div><div class="form-group">
                                          <input class="form-control undefined" name="specializations" placeholder="" value="<?php echo $logindata['specializations']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"></div></div></div></div>
                                        </div>
                                        <div class="col-md-6"><p>Birth Date</p><div class="custom-datepicker"><div class="react-datepicker-wrapper"><div class="react-datepicker__input-container">
                                            <input type="date" data-date-inline-picker="true" name="date_of_birth" id="date_of_birth" class="form-control undefined" value="<?php echo $logindata['date_of_birth']; ?>"></div></div></div>
                                          </div>
                                        </div>
                                        <div class="row"></div><div class="row">
                                          <div class="col-md-6">
                                            <p>Address Line 1</p>
                                            <div><div><div><div class=""></div>
                                            <div class="form-group">
                                               <input class="form-control undefined" name="address1" placeholder="" value="<?php echo $logindata['address1']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                             </div></div></div></div>
                                          </div>
                                          <div class="col-md-6"><p>Address Line 2</p><div><div><div><div class=""></div>
                                            <div class="form-group">
                                              <input class="form-control undefined" name="address2" placeholder="" value="<?php echo $logindata['address2']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"></div></div></div></div>
                                          </div>
                                          <div class="col-md-4"><p>City/Town</p><div><div><div><div class=""></div>
                                            <div class="form-group">
                                              <input class="form-control undefined" name="city" placeholder="" value="<?php echo $logindata['city']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"></div></div></div></div>
                                          </div>
                                          <div class="col-md-4"><p>Province</p><div><div><div><div class=""></div>
                                            <div class="form-group">
                                              <input class="form-control undefined" name="province" placeholder="" value="<?php echo $logindata['province']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"></div></div></div></div>
                                          </div>
                                          <div class="col-md-4"><p>Country</p><div><div><div><div class=""></div>
                                            <div class="form-group">
                                               <input class="form-control undefined" name="country" placeholder="" value="<?php echo $logindata['country']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                            </div></div></div></div>
                                          </div>
                                          <div class="col-md-4"><p>Post Code</p><div><div><div><div class=""></div><div class="form-group">
                                                <input class="form-control undefined" name="postal_code" placeholder="" value="<?php echo $logindata['postal_code']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"></div></div></div></div>
                                          </div>
                                          <div class="col-md-4"><p>Land Phone</p><div><div><div><div class=""></div>
                                            <div class="form-group">
                                              <input class="form-control undefined" name="home_phone" placeholder="" value="<?php echo $logindata['home_phone']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"></div></div></div></div>
                                          </div>
                                          <div class="col-md-4"><p>Mobile Phone</p><div><div><div><div class=""></div>
                                            <div class="form-group">
                                              <input class="form-control undefined" name="mobile_phone" placeholder="" value="<?php echo $logindata['mobile_phone']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"></div></div></div></div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-6"><p>Website</p><div><div><div><div class=""></div><div class="form-group">
                                            <input class="form-control undefined" name="website" id="website" placeholder="" value="<?php echo $logindata['website']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"></div></div></div></div>
                                          </div>
                                          <div class="col-md-6"><p>Twitter</p><div><div><div><div class=""></div>
                                            <div class="form-group">
                                              <input class="form-control undefined" name="twitter" id="twitter" placeholder="" value="<?php echo $logindata['data']['twitter']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                            </div></div></div></div>
                                          </div>
                                        </div>
                                        <div class="row">
                                          <div class="col-md-6"><p>Instagram</p><div><div><div><div class=""></div>
                                            <div class="form-group">
                                              <input class="form-control undefined" name="instagram" id="instagram" placeholder="" value="<?php echo $logindata['data']['instagram']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                            </div></div></div></div>
                                          </div>
                                          <div class="col-md-6"><p>Facebook</p><div><div><div><div class=""></div>
                                            <div class="form-group">
                                              <input class="form-control undefined" name="facebook" id="facebook" placeholder="" value="<?php echo $logindata['data']['facebook']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                            </div></div></div></div>
                                          </div>
                                          <div class="col-md-6"><p>Youtube / Vimeo</p><div><div><div><div class=""></div>
                                            <div class="form-group">
                                              <input class="form-control undefined" name="youtube" id="youtube" placeholder="" value="<?php echo $logindata['data']['youtube']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;">
                                            </div></div></div></div>
                                          </div>
                                          <div class="col-md-6"><p>Linkedin</p><div><div><div>
                                            <div class=""></div>
                                            <div class="form-group">
                                              <input class="form-control undefined" name="linkedin" id="linkedin" placeholder="" value="<?php echo $logindata['data']['linkedin']; ?>" style="border-radius: 0px; box-shadow: none; padding-left: 2%;"></div></div></div></div>
                                          </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        <div class="row mt-3"><div class="col-12 text-right">
                                          <!--<button type="button" class="btn float-none mr-1" style="color: rgb(80, 80, 80); background-color: rgb(221, 221, 221);">Cancel</button>-->
                                          <input type="submit" id="btnUpdate" class="btn ml-1" value="Update">
                                          <div class="post-group customloader" id="inifiniteLoaderUpdate" style="text-align: center; display: none;">
                                            <img src="<?php echo esc_url( plugins_url( 'assets/img/buttonloader.gif', dirname(__FILE__) ) ) ?>" >
                                          </div>
                                          <br/><br/>
                                          <div id="updateMsg" style="display: none;"><center><h3 style="color: green;">Your Information Updated Successfully.</h3></center></div>
                                        </div>
                                      </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br/><br/>
                  <?php if(isset($programData) && !empty($programData)): ?>
                  <div class="eventProgram">
                    <div class="container">
                      <div class="row">
                        <div class="col-md-12">
                          <h3>Event Title</h3>
                          <div class="eventTitle">
                            <h4><?php echo $programData['name']; ?></h4>
                          </div>
                          <?php foreach($programUsers as $key=>$uservalue){
                                    if($uservalue['id'] == $_SESSION['user_id']){
                                      $msg = 'active';
                                    }
                                  }
                          ?>
                          <div class="invoicedata" id="invoicedata" <?php if($msg =='active'){ echo 'style="display: block;"'; }else{ echo 'style="display: none;"'; } ?>>
                            <?php $invoices = invoicesData($account_id,$_SESSION['accesstoken'],$programData['name']);
                                $data = json_decode($invoices);
                                if(!empty($data)){
                                  $allData = $data->invoicedata;
                                }
                                $incId = isset($allData) ? $allData->id : '';
                                $registerId = isset($allData) ? $allData->registration_id : '';
                                $programId = $program_id;
                                $invoiceId = isset($allData) ? $allData->reference : '';
                                $invoiceamount = isset($allData) ? $allData->total_due_cached : '';
                                if(isset($msg) && $msg =='active' && $programId == $program_id){ ?>
                                  <p><b>Invoice Number - <?php echo $invoiceId; ?></b></p>
                                  <p><b>Amount - <?php echo $invoiceamount; ?></b></p>
                            <?php } ?>
                          </div>
                          <div class="registerBtn">
                            <?php if(isset($msg) && $msg =='active' ){ 
                                  if($invoiceId != '' && $programId == $program_id){ ?>
                                    <a href="https://<?php echo $subdomain; ?>.amsnetwork.ca/invoices/<?php echo $incId; ?>/online_payment" id="paymentBtn" target="_blank" class="btn ml-1">Payment</a> | 
                                  <?php } ?>
                                  <a href="javascript:void(0);" id="registerBtn" class="btn ml-1">Registered</a> <?php }else{ ?><a href="javascript:void(0);" id="registerBtn" class="btn ml-1" onclick="registerProgram()">Register</a><?php } ?> | <a href="javascript:void(0);" id="verifyRegisterBtn" class="btn ml-1" onclick="verifyRegistration()">verify registration</a>
                          </div>
                          <br/>
                          <div class="post-group customloader" id="inifiniteLoaderRegisration" style="text-align: center; display: none;">
                            <img src="<?php echo esc_url( plugins_url( 'assets/img/buttonloader.gif', dirname(__FILE__) ) ) ?>" >
                          </div>
                          <div id="verifystatusMsg" class="verifystatus">
                          <br/>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endif; ?>
                <?php
                echo "<input type='hidden' id='getaccesstoken' value='".$_SESSION["accesstoken"]."' />";
                echo "<div class='col-8'><input type='submit' id='btnAMSLogout' class='btn ml-1' onclick='btnAMSLogout()' value='Log Out' /></div>";
                echo "<div class='post-group customloader' id='inifiniteLoader' style='text-align: center; display: none;'>
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
                    
                    <div class="post-group customloader" id="inifiniteLoader" style="text-align: center; display: none;">
                      <img src="<?php echo esc_url( plugins_url( 'assets/img/buttonloader.gif', dirname(__FILE__) ) ) ?>" >
                    </div>
                    <div class="post-group">
                      <h4>
                        <a class="text-info float-right" href="https://<?php echo $subdomain; ?>.amsnetwork.ca/reset_password" target="popup" 
                          onclick="window.open('https://<?php echo $subdomain; ?>.amsnetwork.ca/reset_password','popup','width=600,height=600'); return false;">
                            Forgot password?
                        </a>
                      </h4>
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
    
    jQuery("#updateMsg").hide();
    jQuery("#inifiniteLoader").hide(); 
    jQuery("#inifiniteLoaderUpdate").hide(); 
    
    $('#btnSubmit').click(function(){

        var amsemailoruser = jQuery('#amsemailoruser').val();
        var amspassword = jQuery('#amspassword').val();   
        
          $.ajax({
             url: amsjs_ajax_url.ajaxurl,
             type:'POST',
             data: { action: 'get_amsmemberlogindetails', amsemailoruser:amsemailoruser, amspassword:amspassword},
             beforeSend: function(){
              // Show image container
                 // jQuery("#inifiniteLoader").show();
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
                    jQuery("#amscredentials_error").html('<p>The AMS credentials are incorrect. Please verify your Username or Password.</p>');
                    jQuery("#amscredentials_error").css("color", "red");
                    jQuery("#amscredentials_error").css("display", "block");

                    setTimeout(function() {
                        $('#amscredentials_error').fadeOut('fast');
                    }, 6000);
                 }
             }
          });
    
    });

    var elm;
    function isValidURL(u){
      if(!elm){
        elm = document.createElement('input');
        elm.setAttribute('type', 'url');
      }
      elm.value = u;
      return elm.validity.valid;
    }//end of function
    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }//end of function
    
    $("#userUpdate").on('submit', function (e) {
        var focusSet = false;
        var websiteurl = isValidURL(jQuery('#website').val());
        var facebookurl = isValidURL(jQuery('#facebook').val());
        var twitterurl = isValidURL(jQuery('#twitter').val());
        var instagramurl = isValidURL(jQuery('#instagram').val());
        var linkedinurl = isValidURL(jQuery('#linkedin').val());
        var youtubeurl = isValidURL(jQuery('#youtube').val());
        var email = jQuery('#email').val();
        if(email == ''){
             jQuery("#email").parent().after("<div class='validation' id='emailurl' style='color:red;margin-bottom: 20px;'>Please enter email</div>");
             e.preventDefault(); // prevent form from POST to server
             jQuery('#email').focus();
             focusSet = true;
        }else{
          if(isEmail(email) == false){
             jQuery("#email").parent().next(".validation").remove();          
             jQuery("#email").parent().after("<div class='validation' id='emailurl' style='color:red;margin-bottom: 20px;'>Please enter a valid email</div>");
             e.preventDefault(); // prevent form from POST to server
             jQuery('#email').focus();
            focusSet = true;
          }else{
            jQuery("#email").parent().next(".validation").remove(); // remove it
          }
        }
        if(websiteurl == false){
          if(jQuery('#websiteurl').length == 0){
             $("#website").parent().after("<div class='validation' id='websiteurl' style='color:red;margin-bottom: 20px;'>Please enter a URL with http:// or https://</div>");
             e.preventDefault(); // prevent form from POST to server
             $('#website').focus();
          }
          focusSet = true;
        }else{
          $("#website").parent().next(".validation").remove(); // remove it
        }
        if(facebookurl == false){
          if(jQuery('#facebookurl').length == 0){
             $("#facebook").parent().after("<div class='validation' id='facebookurl' style='color:red;margin-bottom: 20px;'>Please enter a URL with http:// or https://</div>");
             e.preventDefault(); // prevent form from POST to server
             $('#facebook').focus();
          }
          focusSet = true;
        }else{
          $("#facebook").parent().next(".validation").remove(); // remove it
        }
        if(twitterurl == false){
          if(jQuery('#twitterurl').length == 0){
            $("#twitter").parent().after("<div class='validation' id='twitterurl' style='color:red;margin-bottom: 20px;'>Please enter a URL with http:// or https://</div>");
             e.preventDefault(); // prevent form from POST to server
             $('#twitter').focus();
           }
           focusSet = true;
        }else{
          $("#twitter").parent().next(".validation").remove(); // remove it
        }
        if(instagramurl == false){
          if(jQuery('#instagramurl').length == 0){
            $("#instagram").parent().after("<div class='validation' id='instagramurl' style='color:red;margin-bottom: 20px;'>Please enter a URL with http:// or https://</div>");
             e.preventDefault(); // prevent form from POST to server
             $('#instagram').focus();
          }
          focusSet = true;
        }else{
          $("#instagram").parent().next(".validation").remove(); // remove it
        }
        if(linkedinurl == false){
          if(jQuery('#linkedinurl').length == 0){
            $("#linkedin").parent().after("<div class='validation' id='linkedinurl' style='color:red;margin-bottom: 20px;'>Please enter a URL with http:// or https://</div>");
             e.preventDefault(); // prevent form from POST to server
             $('#linkedin').focus();
          }
          focusSet = true;
        }else{
          $("#linkedin").parent().next(".validation").remove(); // remove it
        }
        if(youtubeurl == false){
          if(jQuery('#youtubeurl').length == 0){
            $("#youtube").parent().after("<div class='validation' id='youtubeurl' style='color:red;margin-bottom: 20px;'>Please enter a URL with http:// or https://</div>");
             e.preventDefault(); // prevent form from POST to server
             $('#youtube').focus();
          }
          focusSet = true;
        }else{
          $("#youtube").parent().next(".validation").remove();
        }
        if(focusSet == true){ return false; }
        e.preventDefault(); // prevent actual form submit
        if(confirm("Are you sure want to update the information?")) {
          this.click;
          var form = $(this);
          var user_id = '<?php echo $_SESSION["user_id"]; ?>';
          var access_token = '<?php echo $_SESSION["accesstoken"]; ?>';
          var usersdata = form.serializeArray();
          $.ajax({
             url: amsjs_ajax_url.ajaxurl,
             type:'POST',
             cache: false,
             data: { 
                    action: 'updateUserDetails',
                    user_id:user_id,
                    access_token:access_token,
                    usersdata:usersdata
                   },
             dataType: 'JSON',
             beforeSend: function(){
              // Show image container
                  jQuery("#inifiniteLoaderUpdate").show();
                  jQuery("#btnUpdate").attr("disabled", true);
             },
             success: function (data) {
                jQuery('#inifiniteLoaderUpdate').css('display','none');
                jQuery("#btnUpdate").attr("disabled", false);
                var mydata = data.msg;
                var status = data.status;
               // location.reload();
                if(mydata == 'valid' && status == true)
                {
                  jQuery(".projectslisting #updateMsg").focus();
                  jQuery("#updateMsg").css('display','block');
                  setTimeout(function(){
                     jQuery("#updateMsg").css('display','none');
                  }, 6000);
                }
                else
                {
                  alert('Something error');
                }
             }
          });
        } // condition of confirm box end here
    });
});

function redirectAMSWithKey() 
{
    var user_id = '<?php echo $_SESSION["user_id"]; ?>';
    var organization_id = '<?php echo $organization_id; ?>';
    var user_accesstoken = '<?php echo $_SESSION["accesstoken"]; ?>';
    var encryptedKey = CryptoJS.AES.encrypt(organization_id, "My Secret Passphrase");
    var userIdKey = CryptoJS.AES.encrypt(user_id, "My Secret Passphrase");
    var accessTokenKey = CryptoJS.AES.encrypt(user_accesstoken, "My Secret Passphrase");
    var url = "https://<?php echo $subdomain; ?>.amsnetwork.ca/projects/podcast-fb8a53a3-7209-496d-930a-bb2fd743176d/media?oid=" + encodeURIComponent(encryptedKey);
    jQuery.cookie("organizationId", encryptedKey);
    jQuery.cookie("userId", userIdKey);
    jQuery.cookie("userAccessToken", accessTokenKey);
    window.location.href = url;
    return false;
}

 //start function for use of register user for specific program
 function registerProgram() 
 {
    var user_id = '<?php echo $_SESSION["user_id"]; ?>';
    var program_id = '<?php echo $program_id; ?>';
    var first_name = '<?php echo $logindata["first_name"] ?>';
    var last_name = '<?php echo $logindata["last_name"]; ?>';
    var access_token = '<?php echo $_SESSION["accesstoken"]; ?>';
    jQuery.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       cache: false,
       data: { 
              action: 'programRegistration',
              user_id:user_id,
              program_id:program_id,
              access_token:access_token,
              floating_first_name:first_name,
              floating_last_name:last_name
             },
       dataType: 'JSON',
       beforeSend: function(){
       // Show image container
          jQuery("#inifiniteLoaderRegisration").show();
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
                    jQuery("#inifiniteLoaderRegisration").hide();
                    var programId = program_id;
                    var invoicesData = response.invoicedata;
                    var registerId = invoicesData.registration_id;
                    var invoiceId = invoicesData.reference;
                    var status = invoicesData.status_string;
                    var invoiceamount = invoicesData.total_due_cached;
                    jQuery('#invoicedata').html('<p><b>Invocie Number - </b>'+invoiceId+'</p><p><b>Amount :- </b>'+invoiceamount+'</p>');
                    jQuery('#invoicedata').show();
                    var html = '<a href="javascript:void(0);" id="paymentBtn" class="btn ml-1">Payment</a> | ';
                    jQuery('.registerBtn').prepend(html);
                   }
                });
           } else{
              jQuery("#inifiniteLoaderRegisration").hide();
              alert('Something went wrong try again!');
           }
       }//end of success
    });
 }//end of function

 //start function for use of register user for specific program
 function verifyRegistration() 
 {
    jQuery("#verifystatusMsg").hide();
    var user_id = '<?php echo $_SESSION["user_id"]; ?>';
    var access_token = '<?php echo $_SESSION["accesstoken"]; ?>';
    jQuery.ajax({
       url: amsjs_ajax_url.ajaxurl,
       type:'POST',
       cache: false,
       data: { 
              action: 'verifyRegistrationStatus',
              user_id:user_id,
              access_token:access_token
             },
       dataType: 'JSON',
       beforeSend: function(){
        // Show image container
            jQuery("#inifiniteLoaderRegisration").show();
            //jQuery("#btnSubmit").attr("disabled", true);
       },
       success: function (response) {
         if(response.msg == 'invalid'){
            jQuery("#inifiniteLoaderRegisration").hide();
            jQuery("#verifystatusMsg").html("<p style='color:red;'>No current registration.</p>");
            jQuery("#verifystatusMsg").show();
            setTimeout(function() {
                jQuery('#verifystatusMsg').fadeOut('fast');
            }, 5000);
         } else{
            jQuery("#inifiniteLoaderRegisration").hide();
            var program = response.currentprogram;
            jQuery("#verifystatusMsg").html("<p style='color:green;'><b>Current Registration </b><br/>"+ program +"</p>");
            jQuery("#verifystatusMsg").show();
            setTimeout(function() {
                jQuery('#verifystatusMsg').fadeOut('fast');
            }, 10000);
         }
       }
    });
 }//end of function
</script>

  
<?php
    $ret = ob_get_contents();  
    ob_end_clean(); 
    return $ret; 
}

add_shortcode('amslogin', 'amslogin_function');

?>
