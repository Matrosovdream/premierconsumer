<?php /* Template Name: welcome temp */ 
    get_header();
    $lang = "";
    $fullPath = "";
    if(isset($_GET['lang']) && $_GET['lang'] == "es"){
    	$fullPath = 'https://www.premierconsumer.org/wp-content/uploads/2023/03/welcome-letter-spanish.pdf';
    } else {
    	$fullPath = 'https://www.premierconsumer.org/wp-content/uploads/2023/03/Welcome-Letter-english.pdf';
    }
    
    
?>
	<a href="<?php echo $fullPath; ?>" download id="downloadpdf"></a>
	<script>
		window.onload = function() {
	        document.getElementById('downloadpdf').click();
	    }
	</script>
<?php 
    get_footer();