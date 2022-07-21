<?php
global $wpdb;
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    User_Reviews
 * @subpackage User_Reviews/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="ur_reviews_wrap" class="ur_reviews_wrap">
    <h3 class="ur_title"><?php echo ((get_option('ur_reviews_title')) ? get_option('ur_reviews_title') : 'Recensioni degli utenti'); ?></h3>
    <ul class="ur_reviews">
        <?php
        $post_id = get_post()->ID;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}user_reviews WHERE reference = $post_id AND status = 'approved'");
        if($results){
            foreach($results as $result){
                ?>
                <li class="ur_review">
                    <div class="ur_stars"> <?php echo get_ur_reviews_ratings(intval($result->star)) ?> </div>
                    <div class="ur_useranme"><strong><?php echo $result->name ?></strong></div>
                    <div class="ur_review_contents">
                        <p><?php echo $result->feedback ?></p>
                        <div class="ur_images">
                            <ul>
                                <?php
                                $images = $result->images;
                                $images = unserialize($images);
                                if(is_array($images)){
                                    foreach($images as $image){
                                        if(!empty($image)){
                                            ?>
                                            <li><img src="<?php echo $image ?>"></li>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="preview_image">
                        <img src="">
                    </div>
                </li>
                <?php
            }
        }else{
            echo '<div class="noreviews">Nessuna recensione.</div>';
        }
        ?>
    </ul>

    <div class="ur_reviews_form">
        <form action="" method="post" enctype="multipart/form-data">
            <h3 class="ur_title"><?php echo ((get_option('ur_reviews_form_title'))?get_option('ur_reviews_form_title'):'Scrivi una recensione'); ?></h3>
            <div class="ur_fields">
                <?php if(!is_user_logged_in(  )){ ?>
                <div class="ur_field">
                    <label for="ur_yourname">Your name<span>*</span></label>
                    <input type="text" id="ur_yourname" name="ur_yourname">
                </div>
                <div class="ur_field">
                    <label for="ur_youremail">Your email<span>*</span></label>
                    <input type="text" id="ur_youremail" name="ur_youremail">
                </div>
                <?php } ?>

                <div class="ur_field">
                    <label>Stelle<span>*</span></label>
                    <ul class="ur_stars">
                        <li data-star="1" style="color: #dddddd;"><i class="fas fa-star"></i></li>
                        <li data-star="2" style="color: #dddddd;"><i class="fas fa-star"></i></li>
                        <li data-star="3" style="color: #dddddd;"><i class="fas fa-star"></i></li>
                        <li data-star="4" style="color: #dddddd;"><i class="fas fa-star"></i></li>
                        <li data-star="5" style="color: #dddddd;"><i class="fas fa-star"></i></li>
                    </ul>

                    <input type="hidden" name="selected_star" id="selected_star" value="0">
                </div>
                <div class="ur_field">
                    <label for="ur_feedback">Feedback</label>
                    <textarea name="ur_feedback"></textarea>
                </div>
                <div class="ur_field">
                    <label>Caricare le immagini</label>
                    <div class="ur_img_wrapper">
                        <label for="ur_img_1" class="ur_img_lbl"><i class="far fa-image"></i>
                            <input type="file" name="ur_images1" id="ur_img_1" class="ur_img_file">
                        </label>
                        <label for="ur_img_2" class="ur_img_lbl"><i class="far fa-image"></i>
                            <input type="file" name="ur_images2" id="ur_img_2" class="ur_img_file">
                        </label>
                        <label for="ur_img_3" class="ur_img_lbl"><i class="far fa-image"></i>
                            <input type="file" name="ur_images3" id="ur_img_3" class="ur_img_file">
                        </label>
                        <label for="ur_img_4" class="ur_img_lbl"><i class="far fa-image"></i>
                            <input type="file" name="ur_images4" id="ur_img_4" class="ur_img_file">
                        </label>
                    </div>
                </div>
            </div>
            
            <?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
            <input type="hidden" name="current_url" value="<?php echo $actual_link; ?>">
            <input type="hidden" name="reference" value="<?php echo get_post()->ID; ?>">
            <input type="submit" value="Invia" name="review_submit" class="review_submit">
        </form>
    </div>
</div>