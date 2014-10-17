<?php get_header();?>
<?php 
wp_enqueue_style('lessons',get_template_directory_uri() . '/sections/section-lessons.css');
$args = array (
			'post_type' => 'lesson',
			'posts_per_page' => 12
		); 
$lessons = new WP_Query( $args ); 

?>
<section id="lessons" class="inline">
	<div class="inner">
			<?php if ($lessons->have_posts()) : ?>
				<div class="posts">
					<?php while ($lessons->have_posts()) : ?>
						<?php 
						$lessons->the_post(); 
						$title = get_the_title(); 
						$terms = wp_get_post_terms( get_the_ID(), 'lesson-level' ); 
						if ( is_array($terms) ) {
							$type = !empty($terms[0]->name) ? $terms[0]->name : 'No Category' ; 
							$type_link = !empty($terms[0]->term_id) ? get_term_link($terms[0]->term_id, 'lesson-level') : '' ; 
						}						 
						if ( is_wp_error( $type_link ) ) {
							continue;
						}
						?>
						<?php $posted = sprintf(__("%s at %s"), get_the_date('Y/m/d'), get_the_date('H:i')); ?>
						
						<article class="lesson" >
							<div class="img">
								<a href="<?php echo get_permalink();?>">
									<div class="overlay">Read More</div>
									<?php the_post_thumbnail(); ?>
								</a>
							</div>
							<div class="content">
								<div class="type"><a class="type" href="<?php echo $type_link; ?>" title=""><?php echo $type; ?></a></div>
								<div class="title"><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></div>
							</div>
						</article>
						
					<?php endwhile;?>
				</div>
			<?php else: ?>
				<h3 class="nolessons" ><?php _e('No lessons have been posted.','plaisirdejouer'); ?></h3>
			<?php endif; ?>
	</div>               
</section>

<?php get_footer();?>