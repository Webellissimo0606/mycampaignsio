<?php
if( ! function_exists('login_form_messages') ){
    function login_form_messages( $errors = array(), $success = '', $message = '', $break_top = true ){

        if( ! empty( $errors ) ) { ?>
            <?php if( $break_top ){ ?><br/><?php } ?>
            <div class="msg msg-danger"> <?php
                foreach($errors as $error) {
                    echo 0 === strpos(trim($error), "<p") ? $error : '<p>' . $error . '</p>';
                } ?>
                <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div> <?php
        }

        if( '' !== $success ) { ?>
            <?php if( $break_top ){ ?><br/><?php } ?>
            <div class="msg msg-success" role="alert">
                <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo 0 === strpos(trim($success), "<p") ? $success : '<p>' . $success . '</p>'; ?>
            </div> <?php
        }

        if( '' !== $message ) { ?>
            <?php if( $break_top ){ ?><br/><?php } ?>
            <div class="msg msg-success" role="alert">
                <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo 0 === strpos(trim($message), "<p") ? $message : '<p>' . $message . '</p>'; ?>
            </div> <?php
        }
    }
}

if( ! function_exists('login_form_captcha') ){
    function login_form_captcha($use_recaptcha = false, $config = null, $captcha_html = '', $captcha = ''){

        if( null === $config ){ return; }

        /* Google Recaptcha Part*/
        if ( null !== $use_recaptcha && $use_recaptcha ) { ?>
            <div class="g-recaptcha-wrapper">
                <div class="g-recaptcha" data-size="normal" data-sitekey="<?php echo $config->item('recaptcha_sitekey'); ?>" style="transform:scale(0.88); transform-origin:0; -webkit-transform:scale(0.88); transform:scale(0.88); -webkit-transform-origin:0 0; transform-origin:0 0; margin-left:5.5%;">
                </div>
            </div><?php
        }
        else { ?>
            <div>
                <label>Enter the code as it appears:</label>
                <?php echo $captcha_html; ?>
            </div>
            <div>
                <label>Confirmation Code</label>
                <?php echo form_input($captcha); ?>
            </div> <?php
        }
    }
}