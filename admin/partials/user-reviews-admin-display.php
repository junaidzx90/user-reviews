<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    User_Reviews
 * @subpackage User_Reviews/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h3>Review</h3>
<hr>

<div id="ur_review">
    <div class="ur_review">
        <?php
        global $wpdb;
        $id = intval($_GET['id']);
        if(!$id){
            return;
        }
        $result = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}user_reviews WHERE ID = $id");
        if($result){ ?>
            <table>
                <tr>
                    <th>Reference Post</th>
                    <td>
                        <div class="ur_stars"><a target="_blank" href="<?php echo get_the_permalink( $result->reference ) ?>"><?php echo get_the_title( $result->reference ) ?></a></div>
                    </td>
                </tr>
                <tr>
                    <th>Stars</th>
                    <td>
                        <div class="ur_stars"><?php echo get_ur_reviews_ratings(intval($result->star)) ?> </div>
                    </td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>
                        <div class="ur_useranme"><p><?php echo $result->name ?></p></div>
                    </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <div class="ur_email"><p><?php echo $result->email ?></p></div>
                    </td>
                </tr>
                <tr>
                    <th>Feedback</th>
                    <td>
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
                            <img width="50%" src="">
                        </div>
                    </td>
                </tr>
            </table>
        <?php } ?>
    </div>
</div>