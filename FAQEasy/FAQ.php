<?php
/**
 * Template Name: FAQ
 *
 * @package GeneratePress
 */

get_header(); 
global $wp_query;
$postid = $wp_query->post->ID;
//Queries custom meta to call
$post_meta_data = get_post_custom($post->ID);
$faq = unserialize($post_meta_data['faq_repeatable'][0]);

 ?>




 
<script>
  $(document).ready(function () {
    $('.toggle li').click(function () {
      $('div.panel').slideUp('500');
      $('li').children('span').html('+'); 
      var text = $(this).children('div.panel');
 
      if (text.is(':hidden')) {
     text.slideDown('500');
     $(this).children('span').html('-');        
      } else {
     text.slideUp('500');
     $(this).children('span').html('+');        
      }
        
    });
  });
</script>
<script>
  jQuery(function() {
   $( ".showi" ).tooltip({
      items: "a.showi",
      content: function() {
        var element = $( this );
          return element.attr( "title" );
        
       
      }
      
    });
  });
  </script><style>
 .ui-tooltip {
    padding: 10px 20px;
    color: #505050;
    border-radius: 20px;
    font: bold 14px "Helvetica Neue",Sans-Serif;
    text-align: center;
    line-height: 1.4;
    font-size: 11pt;
    border: 1px solid #1F5484;
}

.toggle {
    padding: 0;
    list-style: none;
    border-bottom: 0 solid #d5d5d5;
    margin-bottom: 35px;
    margin-top: 25px;
    margin-left: 10px;
    margin-right: 10px;
}

.toggle li {
    position: relative;
    background: #f5f5f5;
    border: 1px solid #d5d5d5;
    border-bottom: 0;
    cursor: pointer;
    margin-bottom: 25px;
}

.toggle h3 {
    margin: 0;
    padding: 15px;
    color: #FFF;
    background: #246297;
    font-size: 17px;
    padding-right: 48px;
}

.toggle span {
    position: absolute;
    top: 0;
    right: 0;
    width: 43px;
    height: 100%;
    color: #fff;
    font-size: 30px;
    text-align: center;
    border-left: 1px solid #d5d5d5;
}

.toggle .panel {
    display: none;
    position: relative;
    padding: 10px;
    background: #e5e5e5;
    border-top: 1px solid #d5d5d5;
}

.toggle .panel p {
    padding: 10px 10px 0;
    line-height: 2;
}

.toggle p {
    margin-bottom: 0;
}

.col-md-6 {
    width: 50%;
    float: left;
}
</style>
    <div>
        <div id="primary">
            <main id="main">
                <?php do_action('generate_before_main_content'); ?><?php while ( have_posts() ) : the_post(); ?>
                <h2 style="text-align: center"><?php the_title();?></h2>
                <?php foreach ($faq as $string) { ?>
                <div class="tog">
                    <ul class="toggle">
                        <li>
                            <h3>
                            <?php echo $string["q"];  ?>
                           </h3><p><span>+</span></p>
                            <div class="panel" style="">
                                <p><?php echo $string["a"];?>
                                </p>
                                <p></p>
                            </div>
                        </li>
                    </ul>
                </div>
                 
                <?php }
                      endwhile; // end of the loop. ?><?php do_action('generate_after_main_content'); ?>
            </main><!-- #main -->
        </div><!-- #primary -->
    </div>
<!-- #primary -->

<?php 
do_action('generate_sidebars');
get_footer(); 
print '</div>'; ?>