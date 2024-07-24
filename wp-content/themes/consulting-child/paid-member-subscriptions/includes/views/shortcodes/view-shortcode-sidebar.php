<div class="side-cols side-col1">
    <nav class="pms-account-navigation">
      <ul>
          <?php foreach( $tabs as $slug => $name ) : ?>
              <li class="pms-account-navigation-link pms-account-navigation-link--<?php echo esc_attr( $slug ); ?>">
                  <a class="<?php echo esc_attr( $active_tab == $slug ? 'pms-account-navigation-link--active' : '' ); ?>" href="<?php echo esc_url( ( $slug != 'logout' ? pms_account_get_tab_url( $slug, $account_page ) : wp_logout_url( apply_filters( 'pms_member_account_logout_url', $args['logout_redirect_url'] ) ) ) ); ?>"><?php echo esc_html( $name ); ?></a>
              </li>
          <?php endforeach; ?>
      </ul>
    </nav>
</div>

<div class="side-cols side-col2 upgrade-plan_wid" style="display: none">
    <?php if ( is_active_sidebar( 'upgrade-plan' ) ) : ?>
        <?php dynamic_sidebar( 'upgrade-plan' ); ?>
    <?php endif; ?>

    
</div>
<style type="text/css">
  .error_membership a{
    background: #fec14c;
    display: inline-block;
    padding: 2px 14px;
    font-size: 13px;
    color: #3441a4;
    transition: 0.4s all ease-in-out;

  }
</style>
<?php
$plan_active = "";
$member = pms_get_member( pms_get_current_user_id() );
/*
  echo "<pre>"; print_r($member); die;*/

  if(isset($member->subscriptions) && !empty($member->subscriptions)){
        $subscription_plan_id = $member->subscriptions[0]["subscription_plan_id"];

       
        
        //if($member->subscriptions[0]["status"]  == "active"){
          $subscription_plan = pms_get_subscription_plan( $subscription_plan_id );

          $plan_active = "1";

          if($subscription_plan_id == "7231"){ ?>

            <div class="side-cols side-col1">
                <?php if ( is_active_sidebar( 'max-plus' ) ) : ?>
                    <?php dynamic_sidebar( 'max-plus' ); ?>
                <?php endif; ?>
            </div>
            
          <?php }elseif($subscription_plan_id == "7769" || $subscription_plan_id == "11984"){ ?>
            <div class="side-cols side-col1">
                <?php if ( is_active_sidebar( 'max-pro' ) ) : ?>
                    <?php dynamic_sidebar( 'max-pro' ); ?>
                <?php endif; ?>
            </div>
          <?php }elseif($subscription_plan_id == "7770" || $subscription_plan_id == "12039"){ ?>
            <div class="side-cols side-col1">
                <?php if ( is_active_sidebar( 'max-advanced' ) ) : ?>
                    <?php dynamic_sidebar( 'max-advanced' ); ?>
                <?php endif; ?>
                
            </div>
          <?php }else{
            return  $post =array();
          }
          $plan_upgrades = pms_get_subscription_plan_upgrades( $subscription_plan_id );
            if( !empty( $plan_upgrades ) ){
              echo "<div style='display:none'>";
                echo wp_kses_post( apply_filters( 'pms_output_subscription_plan_action_upgrade', '<a class="pms-account-subscription-action-link pms-account-subscription-action-link__upgrade" href="' . esc_url( wp_nonce_url( add_query_arg( array( 'pms-action' => 'upgrade_subscription', 'subscription_id' => $subscription_plan_id, 'subscription_plan' => $subscription_plan_id ), pms_get_current_page_url( true ) ), 'pms_member_nonce', 'pmstkn' ) ) . '">' . __( 'Upgrade', 'paid-member-subscriptions' ) . '</a>', $subscription_plan, array(), pms_get_current_user_id() ) );
              echo "</div>";  
            }  

            if($member->subscriptions[0]["status"] == "expired"){ 
                $renew_html = "";


                //if( $subscription_plan->duration != '0' ){
                    //  $renew_html =  wp_kses_post( apply_filters( 'pms_output_subscription_plan_action_renewal', '<a class="pms-account-subscription-action-link pms-account-subscription-action-link__renew" href="' . esc_url( wp_nonce_url( add_query_arg( array( 'pms-action' => 'renew_subscription', 'subscription_id' => $subscription_plan_id, 'subscription_plan' => $subscription_plan_id ), pms_get_current_page_url( true ) ), 'pms_member_nonce', 'pmstkn' ) ) . '">' . __( 'Renew', 'paid-member-subscriptions' ) . '</a>', $subscription_plan,array(), pms_get_current_user_id() ) );
                //}             
            ?>                 
                  <script type="text/javascript">
                    jQuery(document).ready(function(){
                      jQuery(".error_membership").html('Your plan has been expired! <br><b style=\'color: #ffffff;font-size: 21px;margin-top: 23px; display: block;text-align:center;\'><?php echo $renew_html;?></b>');
                    })
                    
                  </script>
            <?php } 
            
        //}

        
  }
  
?>


<script type="text/javascript">

    if(jQuery("body").find(".pms-account-subscription-action-link__upgrade") != undefined && jQuery("body").find(".pms-account-subscription-action-link__upgrade").length > 0){
       jQuery("body").find(".upgrade-plan_wid").show();
    }
    jQuery(document).ready(function(){
      if(jQuery("body").find(".pms-account-subscription-action-link__upgrade") != undefined && jQuery("body").find(".pms-account-subscription-action-link__upgrade").length > 0){
         jQuery("body").find(".upgrade-plan_wid").show();
      }
    });
  
</script>
             
<script type="text/javascript">
  jQuery(".upgrade_plan_link").click(function(){
    debugger;
    if(jQuery("body").find(".pms-account-subscription-action-link__upgrade").length > 0){
       
       if(jQuery("body").find(".pms-account-subscription-action-link__upgrade:first").attr("href") != undefined && jQuery("body").find(".pms-account-subscription-action-link__upgrade:first").attr("href") != ""){
         window.location.href = jQuery("body").find(".pms-account-subscription-action-link__upgrade:first").attr("href");
       }
    }
  })
  
</script>