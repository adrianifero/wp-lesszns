<?php get_header(); ?>

<?php if(have_posts()): ?>
<?php while(have_posts()): ?>

<?php the_post(); ?>

<?php 
$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail_size' );
$url = $thumb['0']; 
?>

<section id="title" class="single green">
	<div class="content">
		<h1><?php the_title(); ?></h1>
	</div>
</section>
	
<?php if ( is_user_logged_in() ) : ?> 

<section id="lesson" class="single">
	<div class="content">
	
		<div id="lesson-main" class="white">
			<div id="lesson-list">
				<h3><?php _e('Contents','lessznz');?></h3>
				<ul>
					<li>Video</li>
					<li>Explanation</li>
					<li>Checklist</li>
					<li>Resources</li>
					<li>Examples</li>
					<li>Community</li>
					<li>Quiz</li>
				</ul>
			</div>
			<div id="lesson-video">
				<?php 
				$video = get_post_meta( get_the_ID(), '_lessznz_video', true ); 
				$parts = parse_url($video);
				if (array_key_exists('query', $parts)) {
					parse_str($parts['query'], $query);
				}
				?>
				<iframe width="640" height="360" src="//www.youtube.com/embed/<?php echo $query['v'];?>" frameborder="0" allowfullscreen></iframe>
			</div>
		</div>
		
		<div id="lesson-content" class="white">
			<h3>Explanation</h3>
			<?php the_content();?>
		</div>
		
		<div id="lesson-checklist" class="white">
			<h3>Checklist</h3>
			<ul>
				<li>¿Sabes cómo hacer un acorde mayor?</li>
				<li>¿Sabes cuántas notas lleva un acorde séptima?</li>
				<li>¿Sabes cómo hacer un acorde menor?</li>
				<li>¿Aprendiste la fórmula de acordes?</li>
			</ul>
		</div>
		
		<div id="lesson-resources" class="white">
			<h3>Resources</h3>
			<ul>
				<li>Descarga una pista de audio para practcar</li>
				<li>Baja la tabla de fórmulas de acordes</li>
				<li>Comparte esta lección con tus amigos</li>
			</ul>
		</div>
		
		<?php $youtubeIDs = get_post_meta(get_the_ID(), '_lessznz_examples', true); ?>
		<?php if ( !empty($youtubeIDs) ): ?>
		<div id="lesson-examples" class="white">
			<h3>Examples</h3>
			<ul class="boxes">
				<?php 
				$youtubeIDs = explode(",",$youtubeIDs);
				
				foreach ($youtubeIDs as $youtubeID):
					$youtube = simplexml_load_file('http://gdata.youtube.com/feeds/api/videos/'.$youtubeID.'?v=1');
					$youtubeTitle = (string) $youtube->title;
					$youtubeDescription = (string) $youtube->content;
				?>
					<li>
						<iframe width="400" height="240" src="//www.youtube.com/embed/<?php echo $youtubeID; ?>" frameborder="0" allowfullscreen></iframe>
						<h3><?php echo $youtubeTitle; ?></h3>
						<p><?php echo $youtubeDescription; ?></p>
					</li>			
				<?php
				
				endforeach;
				?>
			</ul>
		</div>
		<?php endif; ?>
		
		<div id="lesson-forum" class="white">
			<h3>Share with the Community</h3>
			<iframe id="forum_embed"
			 src="javascript:void(0)"
			 scrolling="no"
			 frameborder="0"
			 width="900"
			 height="700">
			</iframe>

			<script type="text/javascript">
			 document.getElementById("forum_embed").src =
			  "https://groups.google.com/forum/embed/?place=forum/tucuatro-es" +
			  "&showsearch=false&showpopout=true&wmode=transparent&hl=es_ES&parenturl=" +
			  encodeURIComponent(window.location.href) +
			  "#!topic/tucuatro-es/8MZw584M5SA";
			</script>
		</div>
		
		<div id="lesson-quiz" class="white">
			<h3>Lesson Quiz</h3>
			<?php echo do_shortcode('[questionnaire]'); ?>
		</div>
	
	</div>
</section>


	
<?php else: ?>
<section class="restricted">	
	<div class="content-restricted">
		<h2>This content is only for registered users.</h2>
		<p>To see this content you can <a href="/wp-signup.php">create your account for free</a>. </p>
	</div>
</section>
<?php endif; ?>

<?php endwhile; ?>
<?php endif; ?>

<?PHP get_footer(); ?>