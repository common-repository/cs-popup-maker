<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Cs_Popup_Maker
 * @subpackage Cs_Popup_Maker/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h1 class="wp-heading"><?php esc_html_e('CS Popup Maker', 'cs-popup-maker'); ?></h1>
    <hr class="wp-header-end">
    <div class="cs-pop-intro">
        <h1 class="cs-title">
            Welcome To CS PopUp Maker
        </h1>
        <p>
            We hope you will enjoy using our Free Pop Up Maker plugin. CS PopUp Maker is the easiest and simplest pop up plugin in WordPress Plugin.
            Now you can add a pop up in your website wherever you want. You can add either a image pop up or HTML pop up. The choice is all yours.
            We are always working to make our plugin bug free. However, if you come across any issues, please don't hesitate to contact us so get your
            issue resolved.

            Stay healthy and happy. :)
        </p>
    </div>
    <div class="ui grid cs-popup-maker">
       <div class="twelve wide column">
            <ul class="ui vertical- attached fluid tabular menu">
                 <li class="item active" data-tab="cp-dashboard">
                    <a href="#"><?php  esc_html_e('Info', 'cs-popup-maker'); ?></a>
                </li>
                <?php foreach ( $this->get_settings_sections() as $key => $tab ) { ?>
                    <li class="item" data-tab="<?php echo $tab['id'];?>">
                        <a href="#"><?php printf( __('%s', 'cs-popup-maker'), $tab['title'] ); ?></a>
                    </li>
                <?php } ?>                
            </ul>
        </div>
        <div class="twelve wide stretched column">
            <div class="ui attached tab segment active" data-tab="cp-dashboard" id="cp-dashboard">
                    <h4 class="ui header">Thank you for choosing CS Popup Maker.</h4>
                    <p>CS Popup Maker is a simple WordPress plugin to show a popups in homepage, single page or multiple pages. Currently, the plugins supports image popup but will be updated with new features in near future. Stayed tuned....</p>
                    <p>                        
                        <a class="item" href="https://wordpress.org/support/plugin/cs-popup-maker/reviews/#new-post" target="_new">Review this Plugin</a>
                    </p>
            </div>
            <?php foreach ( $this->get_settings_sections() as $form ) { ?>
                <div class="ui attached tab segment " data-tab="<?php echo $form['id'];?>" id="<?php echo $form['id'];?>">
                    <form class="ui form" method="post" action="options.php">
                       
                        <?php
                            do_action( 'cs_popup_start' . $form['id'], $form );
                            
                            settings_fields( $form['id'] );

                            do_settings_sections( $form['id'] );

                            do_action( 'cs_popup_end' . $form['id'], $form );

                           /*  if( 'cs-popup-html' == $form['id']) {
                                ?>
                                  <h2 class="ui header">Preview</h2>
                                <div class="ui two column grid container">
                                    <div class="column">
                                        <h4 class="ui header">Other Settings</h4>
                                        <p></p>
                                        <p></p>
                                    </div>
                                    <div class="column" id="html-template-popup-preview">                                        
                                        
                                    </div>
                                </div>
                                <?php
                            } */
                        ?>

                        <?php if ( isset( $this->settings_fields[ $form['id'] ] ) ): ?>
                            <div style="padding-left: 10px">
                                <?php submit_button(); ?>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>

</div>
