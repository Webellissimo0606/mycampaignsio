<?php
global $current_page;
global $include_filterable_table;

$include_filterable_table = isset( $include_filterable_table ) && $include_filterable_table;

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

if( ! function_exists('user_profile_data') ){
    function user_profile_data( $db_ins = null, $user_id = 0 ){
        $user_id = (int) $user_id;
        $user_id = $user_id ? $user_id : -1;
        $query  = $db_ins->query( "SELECT * FROM user_profiles WHERE user_id='" . $user_id . "' LIMIT 1" );
        return $query->row();
    }
}

if( ! function_exists('gravatar_thumb') ){
    function gravatar_thumb($email = null, $size = 56){
        if( null === $email){ return ''; }
        
        $default_thumb = site_url().'uploads/images/profile.jpg';
        
        // Alternative default thumb.
        // $default_thumb = base_url() . 'frontend/site/default/new-ui/img/user-thumb.png';
        
        return "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($default_thumb)."&s=".$size;
    }
}

if( ! function_exists('checkbox_component') ){
    function checkbox_component($name='', $checked=false){ 
        $id = '' !== $name ? 'id-' . $name : 'tmp-id-' . substr(uniqid(), -4);
        ?>
        <div class="optio-check-component">
            <input id="<?php echo $id; ?>" class="optio-check" type="checkbox" name="<?php echo $name; ?>" <?php echo $checked ? 'checked' : ''; ?> value="1"/>
            <label for="<?php echo $id; ?>" class="optio-check-btn"></label>
        </div> <?php
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <!-- <link rel="stylesheet" href="https://unpkg.com/tachyons@4.7.0/css/tachyons.min.css" /> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>frontend/site/default/new-ui/assets/css/tachyons.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>frontend/site/default/new-ui/assets/css/styles.min.css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>frontend/site/default/new-ui/assets/js/jquery-3.2.1.min.js"></script>

    <!-- NOTE: New version of Chart.js [2.6.0] has bugs in IE browsers, especially ysing time scales. -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
    
	<?php if( in_array( $current_page, array('login', 'register', 'forgot-password'), true ) ){ ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php } ?>
</head>

<body>