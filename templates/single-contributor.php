<?php
get_header();

while (have_posts()):
    the_post();

    $post_id = get_the_ID();

    $contributor = new WPKCS_Contributor($post_id);

    $username = $contributor->get_username();

    $link = get_post_meta(
        get_the_ID(),
        '_wpkcs_link',
        true
    );

    ?>




    <div class="contributor-profile">


        <div class="contributor-top">


            <div class="contributor-image">

            <?php
            if ( has_post_thumbnail() ) {

                the_post_thumbnail(
                    'medium',
                    array(
                        'class' => 'profile-img'
                    )
                );

            } elseif ( $avatar = $contributor->get_avatar(650) ) { ?>

                    <img
                        src="<?php echo esc_url( $avatar ); ?>"
                        alt="<?php the_title_attribute(); ?>"
                        class="profile-img"
                    >

            <?php } ?>

                

            </div>



            <div class="contributor-info">


                <h2>
                    <?= $contributor->full_name(); ?>
                </h2>



                <div class="bio">

                    <?php the_content(); ?>

                    <?= $contributor->get_bio(); ?>

                </div>

                <a class="profile-btn" href="https://profiles.wordpress.org/<?php echo ($username); ?>"
                    target="_blank">WORDPRESS.ORG PROFILE</a>

                

                <div class="social-icons share-icons">

                    <?php
                    $share_url   = urlencode( get_permalink() );
                    $share_title = urlencode( get_the_title() );
                    ?>

                    <a href="https://www.facebook.com/sharer.php?u=<?php echo $share_url; ?>"
                    target="_blank"
                    class="share-icon facebook">
                        f
                    </a>


                    <a href="https://twitter.com/share?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>"
                    target="_blank"
                    class="share-icon twitter">
                        𝕏
                    </a>


                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>"
                    target="_blank"
                    class="share-icon linkedin">
                        in
                    </a>


                    <a href="mailto:?subject=<?php echo $share_title; ?>&body=<?php echo $share_url; ?>"
                    class="share-icon email">
                        ✉
                    </a>


                    <a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>"
                    target="_blank"
                    class="share-icon pinterest">
                        P
                    </a>


                    <a href="https://t.me/share/url?url=<?php echo $share_url; ?>"
                    target="_blank"
                    class="share-icon telegram">
                        ➤
                    </a>

                </div>

                


            </div>


        </div>




        <?php


        $contributions = $contributor->get_user_contributions();



        $current_type = isset($_GET['type'])
            ? sanitize_text_field($_GET['type'])
            : ($contributions[0]['type'] ?? '');


        ?>



        <div class="contributor-tabs">

            <ul>


                <?php foreach ($contributions as $contribution){
                    if($contribution['type'] === $current_type){
                        $contribution_data = $contribution['data'];
                    }
                    ?>


                    <li class="<?php echo $contribution['type'] === $current_type ? 'active' : ''; ?>">


                        <a href="<?php echo esc_url(
                            add_query_arg(
                                'type',
                                $contribution['type']
                            )
                        ); ?>">


                            <?php echo $contribution['name']; ?>


                        </a>


                    </li>


                <?php } ?>


            </ul>

        </div>




        <?php





        if (true):


            ?>


            <div class="contribution-list">



                <?php foreach ($contribution_data ?? [] as $key => $value) {
                    


                    $screenshot = get_post_meta(

                        $value['ID'],

                        '_wpkcs_screenshot',

                        true

                    );


                    $date = $value['date'];


                    $time = get_post_meta(

                        $value['ID'],

                        '_wpkcs_time_spent',

                        true

                    );


                    $title = get_post_meta(

                        $value['ID'],

                        '_wpkcs_title',

                        true

                    );
                    $link = get_post_meta(

                        $value['ID'],

                        '_wpkcs_link',

                        true

                    );

                    if ($current_type == 'Photos Contribution'){
                        ?>
                        <div class="photo-contribution" ><?php echo wp_get_attachment_image($screenshot,'small'); ?></div>
                        <?php
                        continue;
                    }


                    ?>
                    <div class="contribution-item">

                        <?php if ($current_type == 'Code Contribution') : ?>
                        <h3></h3>
                        <?php endif; ?>


                        <?php if ($current_type == 'Code Contribution') : ?>
                        <div>
                            <a href="<?= $link ?>" ><?= $title ?></a>
                        </div>
                        <?php endif; ?>




                        <div class="contribution-meta">


                            <span>

                                Date:
                                <?php echo esc_html($date); ?>

                            </span>




                        </div>



                        <?php

                        if ($screenshot) {


                            echo wp_get_attachment_image(

                                $screenshot,

                                'medium'

                            );


                        }


                        ?>



                    </div>



                <?php } ?>


            </div>



            <?php

        else:

            ?>


            <!-- <p>
                No contributions found.
            </p> -->


            <?php

        endif;


        wp_reset_postdata();


        ?>


    </div>


    <?php

endwhile;


get_footer();

?>