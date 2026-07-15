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

    <style>
        .contributor-profile {
            max-width: 900px;
            margin: 40px auto;
            font-family: Arial, sans-serif;
        }

        .contributor-top {
            display: flex;
            gap: 25px;
        }

        .contributor-image {
            width: 180px;
        }

        .profile-img {
            width: 180px;
            height: 270px;
            object-fit: cover;
        }

        .contributor-info {
            flex: 1;
        }

        .contributor-info h2 {
            font-size: 22px;
        }

        .bio {
            font-size: 14px;
            line-height: 1.7;
        }

        .profile-btn {
            display: inline-block;
            background: #00a32a;
            color: #fff;
            padding: 8px 15px;
            margin-top: 20px;
            text-decoration: none;
            font-size: 12px;
        }


        .social-icons {
            margin-top: 20px;
        }

        .social-icons span {
            border: 1px solid #ddd;
            padding: 5px 8px;
            margin-right: 5px;
        }


        .contributor-tabs ul {

            display: flex;
            justify-content: center;
            gap: 25px;
            list-style: none;

            padding: 15px 0;

            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;

        }


        .contributor-tabs a {

            color: #555;
            text-decoration: none;
            font-size: 12px;

        }


        .contributor-tabs .active a {

            color: #00a32a;
            font-weight: bold;

        }


        .contribution-item {

            padding: 20px 0;
            border-bottom: 1px solid #eee;

        }


        .contribution-item h3 {

            font-size: 18px;

        }


        .contribution-meta {

            margin-top: 10px;
            font-size: 12px;
            color: #777;

        }


        .contribution-meta span {

            margin-right: 15px;

        }
        .social-icons {
            margin-top:25px;
            display:flex;
            gap:8px;
        }


        .share-icon {

            width:34px;
            height:34px;

            border:1px solid #ddd;
            border-radius:50%;

            display:flex;
            align-items:center;
            justify-content:center;

            text-decoration:none;

            color:#555;

            font-size:14px;

            transition:all .3s ease;

        }


        .share-icon:hover {

            background:#00a32a;
            border-color:#00a32a;
            color:#fff;

        }


        .facebook:hover {
            background:#1877f2;
            border-color:#1877f2;
        }


        .twitter:hover {
            background:#000;
            border-color:#000;
        }


        .linkedin:hover {
            background:#0a66c2;
            border-color:#0a66c2;
        }


        .pinterest:hover {
            background:#e60023;
            border-color:#e60023;
        }


        .telegram:hover {
            background:#229ed9;
            border-color:#229ed9;
        }
    </style>



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
                    <?php the_title(); ?>
                </h2>



                <div class="bio">

                    <?php the_content(); ?>

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


        $types = array(

            'Code Contribution' => 'CODE',

            'Learn WordPress' => 'LEARN',

            'Meetup' => 'MEETUPS',

            'Photos Contribution' => 'PHOTOS',

            'Translation' => 'TRANSLATIONS',

            'Support Forum' => 'SUPPORT FORUM',

            'Documentation' => 'DOCUMENTATION',

            'Other' => 'OTHER',

        );



        $current_type = isset($_GET['type'])
            ? sanitize_text_field($_GET['type'])
            : 'Code Contribution';


        ?>



        <div class="contributor-tabs">

            <ul>


                <?php foreach ($types as $key => $label): ?>


                    <li class="<?php echo $current_type === $key ? 'active' : ''; ?>">


                        <a href="<?php echo esc_url(
                            add_query_arg(
                                'type',
                                $key
                            )
                        ); ?>">


                            <?php echo esc_html($label); ?>


                        </a>


                    </li>


                <?php endforeach; ?>


            </ul>

        </div>




        <?php


        $contribution_query = new WP_Query(

            array(

                'post_type' => 'wpkcs_contribution',

                'posts_per_page' => 5,

                'orderby' => 'date',

                'order' => 'DESC',


                'meta_query' => array(

                    'relation' => 'AND',


                    array(

                        'key' => '_wpkcs_username',

                        'value' => $username,

                    ),


                    array(

                        'key' => '_wpkcs_type',

                        'value' => $current_type,

                    )

                )


            )

        );



        if ($contribution_query->have_posts()):


            ?>


            <div class="contribution-list">



                <?php while ($contribution_query->have_posts()):

                    $contribution_query->the_post();


                    $screenshot = get_post_meta(

                        get_the_ID(),

                        '_wpkcs_screenshot',

                        true

                    );


                    $date = get_post_meta(

                        get_the_ID(),

                        '_wpkcs_date',

                        true

                    );


                    $time = get_post_meta(

                        get_the_ID(),

                        '_wpkcs_time_spent',

                        true

                    );


                    ?>



                    <div class="contribution-item">


                        <h3>

                            <?php the_title(); ?>

                        </h3>



                        <div>

                            <?php the_content(); ?>

                        </div>




                        <div class="contribution-meta">


                            <span>

                                Date:
                                <?php echo esc_html($date); ?>

                            </span>



                            <span>

                                Time:
                                <?php echo esc_html($time); ?>

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



                <?php endwhile; ?>


            </div>



            <?php

        else:

            ?>


            <p>
                No contributions found.
            </p>


            <?php

        endif;


        wp_reset_postdata();


        ?>


    </div>


    <?php

endwhile;


get_footer();

?>