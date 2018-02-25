<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php if ($this->ci_auth->is_logged_in()) { ?>
<!-- Footer -->

<div class="footer clearfix">
  <div class="fotter_content">&copy; <?php echo date('Y');?>. <a href="<?php echo site_url();?>"><?php echo $this->config->item('website_name');?></a> by <a target="_blank" href="http://www.1stcoder.com/ci-membership/">1stCoder</a></div>
</div>
<!-- /footer -->
</div>
<!-- /page content -->
</div>
<!-- /page container -->
<?php } else { ?>
<!-- Footer -->
<div class="footer clearfix">
  <div class="fotter_content">&copy; <?php echo date('Y');?>. <a href="<?php echo site_url();?>"><?php echo $this->config->item('website_name');?></a> by <a target="_blank" href="http://www.1stcoder.com/ci-membership/">1stCoder</a></div>
</div>
<!-- /footer -->
<?php } ?>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/multiselect.min.js"></script>
<script type="text/javascript">
    var jq = $.noConflict();
                jq(document).ready(function () {
                    // make code pretty
                    window.prettyPrint && prettyPrint();

                    if (window.location.hash) {
                        scrollTo(window.location.hash);
                    }

                    jq('.nav').on('click', 'a', function (e) {
                        scrollTo(jq(this).attr('href'));
                    });
                

                    jq('[name="q"]').on('keyup', function (e) {
                        var search = this.value;
                        var $options = jq(this).next('select').find('option');

                        $options.each(function (i, option) {
                            if (option.text.indexOf(search) > -1) {
                                jq(option).show();
                            } else {
                                jq(option).hide();
                            }
                        });
                    });
                   
                   

                    jq('#undo_redo').multiselect({
                        search: {
                            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                        }
                    });
                    
                });

                function scrollTo(id) {
                    if (jq(id).length) {
                        jq('html,body').animate({scrollTop: jq(id).offset().top - 40}, 'slow');
                    }
                }
            </script>
</body></html>