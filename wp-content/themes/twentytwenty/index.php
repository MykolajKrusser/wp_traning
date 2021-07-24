<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>

<main id="site-content" role="main">

	<?php

	$archive_title    = '';
	$archive_subtitle = '';

	if ( is_search() ) {
		global $wp_query;

		$archive_title = sprintf(
			'%1$s %2$s',
			'<span class="color-accent">' . __( 'Search:', 'twentytwenty' ) . '</span>',
			'&ldquo;' . get_search_query() . '&rdquo;'
		);

		if ( $wp_query->found_posts ) {
			$archive_subtitle = sprintf(
				/* translators: %s: Number of search results. */
				_n(
					'We found %s result for your search.',
					'We found %s results for your search.',
					$wp_query->found_posts,
					'twentytwenty'
				),
				number_format_i18n( $wp_query->found_posts )
			);
		} else {
			$archive_subtitle = __( 'We could not find any results for your search. You can give it another try through the search form below.', 'twentytwenty' );
		}
	} elseif ( is_archive() && ! have_posts() ) {
		$archive_title = __( 'Nothing Found', 'twentytwenty' );
	} elseif ( ! is_home() ) {
		$archive_title    = get_the_archive_title();
		$archive_subtitle = get_the_archive_description();
	}

	if ( $archive_title || $archive_subtitle ) {
		?>

		<header class="archive-header has-text-align-center header-footer-group">

			<div class="archive-header-inner section-inner medium">

				<?php if ( $archive_title ) { ?>
					<h1 class="archive-title"><?php echo wp_kses_post( $archive_title ); ?></h1>
				<?php } ?>

				<?php if ( $archive_subtitle ) { ?>
					<div class="archive-subtitle section-inner thin max-percentage intro-text"><?php echo wp_kses_post( wpautop( $archive_subtitle ) ); ?></div>
				<?php } ?>

			</div><!-- .archive-header-inner -->

		</header><!-- .archive-header -->

		<?php
	}

	if ( have_posts() && get_post_type() != 'events' ) {

		$i = 0;

		while ( have_posts() ) {
			$i++;
			if ( $i > 1 ) {
				echo '<hr class="post-separator styled-separator is-style-wide section-inner" aria-hidden="true" />';
			}
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

		}
        get_template_part('template-parts/pagination');
    } elseif ( is_search() ) {
		?>

		<div class="no-search-results-form section-inner thin">

			<?php
			get_search_form(
				array(
					'aria_label' => __( 'search again', 'twentytwenty' ),
				)
			);
			?>

		</div><!-- .no-search-results -->
        <?php get_template_part( 'template-parts/pagination' );

	}elseif (get_post_type() == 'events'){

        add_action( 'pre_get_posts', 'wd_event_query_by_date' );
        function wd_event_query_by_date( $query ) {
            // Check that we are on the events CPT archive, the main query and not on in the admin
            if( $query->is_main_query() && !is_admin() && $query->is_post_type_archive( 'events' ) ) {
                $meta_query = array(
                    array(
                        'key'     => 'timestamp', // your event meta here
                        'value'   => date('Y-m-d'),
                        'type'    => 'DATE',
                        'compare' => '>=' // only show dates matching the current date or in the future
                    )
                );
                $query->set( 'posts_per_page', -1 ); // show all posts
                $query->set( 'meta_query', $meta_query );
                $query->set( 'order', 'ASC' ); // sort showing most recent date first
                $query->set( 'orderby', 'meta_value' );
                $query->set( 'meta_key', 'timestamp' );
            }

        }
        // Sort using WP_Query
        $events_query = new WP_Query(array(
            'post_type'      	  => 'events',
            'posts_per_page'	  => -1,
            'post_status'		  => 'publish',
            'meta_key'            => 'timestamp',
            'orderby'          	  => 'meta_value',
            'order'               => 'ASC',
            'meta_query'          => array(
                array(
                    'key'     => 'timestamp',
                    'value'   => date('Y-m-d'),
                    'type'    => 'DATE',
                    'compare' => '>='
                )
            ),
        ));

        ?>
        <div class="section-inner" >
        <?php
            while ( $events_query->have_posts() ) : $events_query->the_post();
            ?>

            <div style="border-bottom: 1px solid #ccc">
                <h3>
                    <?php the_title(); ?>
                </h3>
                <p>
                    <?php the_excerpt(); ?>
                </p>
                <p>
                    <?php the_field('timestamp');?>
                </p>
            </div>

            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
            <?php
        }
	?>


</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php
get_footer();
