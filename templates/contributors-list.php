<div class="wpkcs-contributors-grid">

    <?php
    if ( $query->have_posts() ) :

        while ( $query->have_posts() ) :
            $query->the_post();

            $post_id = get_the_ID();

            $contributor = new WPKCS_Contributor( $post_id );

            ?>

            <div class="wpkcs-contributor-card" style="cursor: pointer;" onclick="window.location.href ='<?= "/contributions/?p=".$contributor->get_username(); ?>'" >

                <div class="wpkcs-card-avatar">

                    <?php if ( $contributor->get_avatar() ) : ?>

                        <img
                            src="<?php echo esc_url( $contributor->get_avatar() ); ?>"
                            alt="<?php the_title_attribute(); ?>"
                        >

                    <?php else : ?>

                        <div class="wpkcs-card-placeholder">

                            <?php
                            echo esc_html(
                                strtoupper(
                                    substr(
                                        get_the_title(),
                                        0,
                                        1
                                    )
                                )
                            );
                            ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="wpkcs-card-content">

                    <h3 class="wpkcs-card-name">
                        <?= get_post_meta($post_id,"_wpkcs_org_name",true); ?>
                    </h3>

                    <div class="wpkcs-card-username">
                        @
                        <?php echo esc_html( $contributor->get_username() ); ?>
                    </div>

                    <!-- <div class="wpkcs-card-bio">
                        <?php //esc_html( $bio ); ?>
                    </div> -->

                    <div class="wpkcs-card-footer">

                        <span class="wpkcs-card-count">

                            <?php
                            echo esc_html( $contributor->get_cotribution_count() );
                            ?>

                            Contributions

                        </span>

                        <!-- <a
                            href="<?php echo esc_url( add_query_arg( 'profile', $contributor->get_username(), get_permalink() ) ); ?>"
                            class="wpkcs-card-button"
                        >
                            View Profile
                        </a> -->

                    </div>

                </div>

            </div>

            <?php

        endwhile;

        wp_reset_postdata();

    else :

        echo '<p>No contributors found.</p>';

    endif;
    ?>

</div>