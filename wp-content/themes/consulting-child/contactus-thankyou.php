<?php /* Template Name: ContactUs Thank You */ ?>

<?php 
    get_header();
?>
<div class="contact-thanks">
	<h1 class="thanks-title"><?php echo get_the_title();?></h1>
	<?php //echo "<pre>";print_r($_GET); ?>
	<h5>We will get in touch with you Soon.</h5>
	<p>Here is the information you have shared with us.</p> 
 	<div class="contact-data row">
 		<?php if(isset($_GET['department'])){ ?>
 		<div class="col-mod-4">
 			<span>Department:</span>
 			<p><?php echo $_GET['department'];?></p>
 		</div>
	 	<?php } ?>
	 	<?php if(isset($_GET['name'])){ ?>
 		<div class="col-mod-4">
 			<span>Name:</span>
 			<p><?php echo $_GET['name'];?></p>
 		</div>
 		<?php } ?>
 		<?php if(isset($_GET['email'])){ ?>
 		<div class="col-mod-4">
 			<span>Email:</span>
 			<p><?php echo $_GET['email'];?></p>
 		</div>
 		<?php } ?>
 		<?php if(isset($_GET['phone'])){ ?>
 		<div class="col-mod-4">
 			<span>Phone:</span>
 			<p><?php echo $_GET['phone'];?></p>
 		</div>
 		<?php } ?>
 		<?php if(isset($_GET['subject'])){ ?>
 		<div class="col-mod-4">
 			<span>Subject:</span>
 			<p><?php echo $_GET['subject'];?></p>
 		</div>
 		<?php } ?>
 		<div class="col-mod-4">
 			<span>Message:</span>
 			<p><?php echo $_GET['your-message'];?></p>
 		</div>
 	</div>
 	<div class="Back-btn">
 		<a href="<?php echo home_url();?>">Back ></a>
 	</div>
</div> 		
<?php get_footer();
?>