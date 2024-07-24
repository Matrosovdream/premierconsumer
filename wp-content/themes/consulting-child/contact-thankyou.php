<?php /* Template Name: Contact Thank You */ ?>

<?php 
get_header();?>
<div class="contact-thanks">
	<h1 class="thanks-title"><?php echo get_the_title();?></h1>
	<?php $test = $_GET;
	$lang = $_GET['lang'];
	//echo "<pre>";print_r($test);
	if($_GET['lang']){?>
	<h5>Nos pondremos en contacto contigo pronto.</h5>
	<?php if($_GET['text-name']){ ?>
	<p>Aquí está la información que ha compartido con nosotros.</p>
	<?php } ?>
	<div class="contact-data row">
		<?php if($_GET['text-name']){ ?>
		<div class="col-mod-4">
			<span>Nombre:</span>
			<p><?php echo $_GET['text-name'];?></p>
		</div>
		<?php } ?>
		<?php if($_GET['nm']){ ?>
		<div class="col-mod-4">
			<span>Nombre:</span>
			<p><?php echo ucwords($_GET['nm']);?></p>
		</div>
		<?php } ?>
		<?php if(isset($_GET['lnm'])){ ?>
 		<div class="col-mod-4">
 			<span>Apellido:</span>
 			<p><?php echo ucwords($_GET['lnm']);?></p>
 		</div>
 		<?php } ?>
 		<?php if($_GET['em']){ ?>
		<div class="col-mod-4">
			<span>Correo electrónico:</span>
			<p><?php echo $_GET['em'];?></p>
		</div>
		<?php } ?>
		<?php if($_GET['email']){ ?>
		<div class="col-mod-4">
			<span>Correo electrónico:</span>
			<p><?php echo $_GET['email'];?></p>
		</div>
		<?php } ?>
		<?php if($_GET['text-phone']){ ?>
		<div class="col-mod-4">
			<span>Teléfono:</span>
			<p><?php echo $_GET['text-phone'];?></p>
		</div>
		<?php } ?>
		<?php if($_GET['phn']){ ?>
		<div class="col-mod-4">
			<span>Teléfono:</span>
			<p><?php echo $_GET['phn'];?></p>
		</div>
		<?php } ?>
		<?php if($_GET['text-id']){ ?>
		<div class="col-mod-4">
			<span>Número de identificación:</span>
			<p><?php echo $_GET['text-id'];?></p>
		</div>
		<?php } ?>	
		<?php if($_GET['textarea-suggestion']){ ?>
		<div class="col-mod-4">
			<span>Sugerencia:</span>
			<p><?php echo $_GET['textarea-suggestion'];?></p>
		</div>
		<?php } ?>	
		<?php if($_GET['dbt']){ ?>
		<div class="col-mod-4">
			<span>Monto de la deuda:</span>
			<p><?php echo $_GET['dbt'];?></p>
		</div>
		<?php } ?>
		<?php if(isset($_GET['dp'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Departamento:</span>
	 			<p><?php echo $_GET['dp'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['nm'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Nombre:</span>
	 			<p><?php echo ucwords($_GET['nm']);?></p>
	 		</div>
	 		<?php } ?>
	 		<?php if(isset($_GET['lnm'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Apellido:</span>
	 			<p><?php echo ucwords($_GET['nm']);?></p>
	 		</div>
	 		<?php } ?>
	 		<?php if(isset($_GET['em'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Correo electrónico:</span>
	 			<p><?php echo $_GET['em'];?></p>
	 		</div>
	 		<?php } ?>
	 		<?php if(isset($_GET['phn'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Número de teléfono:</span>
	 			<p><?php echo $_GET['phn'];?></p>
	 		</div>
	 		<?php } ?>
	 		<?php if(isset($_GET['sub'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Sujeto:</span>
	 			<p><?php echo $_GET['sub'];?></p>
	 		</div>
	 		<?php } ?>
	 		<?php if(isset($_GET['msg'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Mensaje:</span>
	 			<p><?php echo $_GET['msg'];?></p>
	 		</div>
	 		<?php } ?>
	 		<!-- Questions -->
	 		<?php if(isset($_GET['q1'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Usted piensa que Premier Consumer le ha brindado información útil para salir de sus deudas y educado financieramente?:</span>
	 			<p><?php echo $_GET['q1'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q2'])){ ?>
	 		<div class="col-mod-4">
	 			<span>¿Considera que nuestros asesores de crédito certificados son expertos y amables?:</span>
	 			<p><?php echo $_GET['q2'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q3'])){ ?>
	 		<div class="col-mod-4">
	 			<span>¿Le ha resultado fácil navegar en este sitio web?:</span>
	 			<p><?php echo $_GET['q3'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q4'])){ ?>
	 		<div class="col-mod-4">
	 			<span>¿Le ha resultado útil e informativo el centro de aprendizaje?:</span>
	 			<p><?php echo $_GET['q4'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q5'])){ ?>
	 		<div class="col-mod-4">
	 			<span>¿Ha cumplido Premier Consumer Credit Counseling con sus expectativas y sus necesidades?:</span>
	 			<p><?php echo $_GET['q5'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q6'])){ ?>
	 		<div class="col-mod-4">
	 			<span>¿Nuestro personal ha atendido sus inquietudes, por Internet o por teléfono, de forma rápida?:</span>
	 			<p><?php echo $_GET['q6'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q7'])){ ?>
	 		<div class="col-mod-4">
	 			<span>¿Nuestra organización a mejorado sus servicios desde que comenzó con nuestro programa?:</span>
	 			<p><?php echo $_GET['q7'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q8'])){ ?>
	 		<div class="col-mod-4">
	 			<span>¿Le ha resultado cómodo el horario de oficina de nuestra organización?:</span>
	 			<p><?php echo $_GET['q8'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q9'])){ ?>
	 		<div class="col-mod-4">
	 			<span>¿Recomendaría a familiares y amigos nuestra organización?:</span>
	 			<p><?php echo $_GET['q9'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q10'])){ ?>
	 		<div class="col-mod-4">
	 			<span>¿Siente que nuestra organización está al tanto de sus progresos?:</span>
	 			<p><?php echo $_GET['q10'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q11'])){ ?>
	 		<div class="col-mod-4">
	 			<span>¿Siente que es el camino hacia su libertad financiera?:</span>
	 			<p><?php echo $_GET['q11'];?></p>
	 		</div>
		 	<?php } ?>

		 	<?php if(isset($_GET['q12'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Por favor, agregue sus sugerencias en el siguiente cuadro.:</span>
	 			<p><?php echo $_GET['q12'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q13'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Identificación de cliente:</span>
	 			<p><?php echo $_GET['q13'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q14'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Nombre cliente:</span>
	 			<p><?php echo $_GET['q14'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q15'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Si desea que nos pongamos en contacto con usted, haga clic en la siguiente casilla.:</span>
	 			<p><?php echo $_GET['q15'];?></p>
	 		</div>
		 	<?php } ?>
 	</div>
 	<div class="Back-btn">
 		<a href="<?php echo home_url();?>">Atrás ></a>
 	</div>
	<?php }else{?>
	<h5>We will get in touch with you Soon.</h5>
	<?php if($_GET['text-name']){ ?>
	<p>Here is the information you have shared with us.</p> 
	<?php } ?>
 	<div class="contact-data row">
 		<?php if($_GET['text-name']){ ?>
 		<div class="col-mod-4">
 			<span>Name:</span>
 			<p><?php echo $_GET['text-name'];?></p>
 		</div>
 		<?php } ?>
 		<?php if($_GET['nm']){ ?>
 		<div class="col-mod-4">
 			<span>Name:</span>
 			<p><?php echo $_GET['nm'];?></p>
 		</div>
 		<?php } ?>
 		<?php if($_GET['email']){ ?>	
 		<div class="col-mod-4">
 			<span>Email:</span>
 			<p><?php echo $_GET['email'];?></p>
 		</div>
 		<?php } ?>
 		<?php if($_GET['em']){ ?>	
 		<div class="col-mod-4">
 			<span>Email:</span>
 			<p><?php echo $_GET['em'];?></p>
 		</div>
 		<?php } ?>
 		<?php if($_GET['text-phone']){ ?>
 		<div class="col-mod-4">
 			<span>Phone:</span>
 			<p><?php echo $_GET['text-phone'];?></p>
 		</div>
 		<?php } ?>
 		<?php if($_GET['phn']){ ?>
 		<div class="col-mod-4">
 			<span>Phone:</span>
 			<p><?php echo $_GET['phn'];?></p>
 		</div>
 		<?php } ?>
 		<?php if($_GET['text-id']){ ?>
		<div class="col-mod-4">
			<span>ID Number:</span>
			<p><?php echo $_GET['text-id'];?></p>
		</div>
		<?php } ?>
		<?php if($_GET['textarea-suggestion']){ ?>
		<div class="col-mod-4">
			<span>Suggestion:</span>
			<p><?php echo $_GET['textarea-suggestion'];?></p>
		</div>
		<?php } ?>
		<?php if($_GET['dbt']){ ?>
		<div class="col-mod-4">
			<span>Debt Amount:</span>
			<p><?php echo $_GET['dbt'];?></p>
		</div>
		<?php } ?>
		<?php if(isset($_GET['dp'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Department:</span>
	 			<p><?php echo $_GET['dp'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['nm'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Name:</span>
	 			<p><?php echo ucwords($_GET['nm']);?></p>
	 		</div>
	 		<?php } ?>
	 		<?php if(isset($_GET['em'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Email:</span>
	 			<p><?php echo $_GET['em'];?></p>
	 		</div>
	 		<?php } ?>
	 		<?php if(isset($_GET['phn'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Phone:</span>
	 			<p><?php echo $_GET['phn'];?></p>
	 		</div>
	 		<?php } ?>
	 		<?php if(isset($_GET['sub'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Subject:</span>
	 			<p><?php echo $_GET['sub'];?></p>
	 		</div>
	 		<?php } ?>
	 		<?php if(isset($_GET['msg'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Message:</span>
	 			<p><?php echo $_GET['msg'];?></p>
	 		</div>
	 		<?php } ?>
	 		<!-- Questions -->
	 		<?php if(isset($_GET['q1'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Do you feel Premier Consumer Credit Counseling has provided you with useful information on how to become debt free and financially educated?:</span>
	 			<p><?php echo $_GET['q1'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q2'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Do you feel that our certified credit counselors are helpful, knowledgeable and courteous?:</span>
	 			<p><?php echo $_GET['q2'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q3'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Have you found this website easy to navigate?:</span>
	 			<p><?php echo $_GET['q3'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q4'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Have you found the learning center useful and informative?:</span>
	 			<p><?php echo $_GET['q4'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q5'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Has Premier Consumer Credit Counseling met your expectations and your needs?:</span>
	 			<p><?php echo $_GET['q5'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q6'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Have our counselor and staff responded to your concerns by internet or phone fast enough?:</span>
	 			<p><?php echo $_GET['q6'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q7'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Has our organization improved its services since you started with our program?:</span>
	 			<p><?php echo $_GET['q7'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q8'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Have you found the office hours of our organization convenient?:</span>
	 			<p><?php echo $_GET['q8'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q9'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Would you recommend family and friends to our organization?:</span>
	 			<p><?php echo $_GET['q9'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q10'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Have you felt that our organization is keeping track of your progress?:</span>
	 			<p><?php echo $_GET['q10'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q11'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Do you feel you are the road to your financial freedom?:</span>
	 			<p><?php echo $_GET['q11'];?></p>
	 		</div>
		 	<?php } ?>

		 	<?php if(isset($_GET['q12'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Please add any suggestion in the following box.:</span>
	 			<p><?php echo $_GET['q12'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q13'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Client ID number:</span>
	 			<p><?php echo $_GET['q13'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q14'])){ ?>
	 		<div class="col-mod-4">
	 			<span>Client ID name:</span>
	 			<p><?php echo $_GET['q14'];?></p>
	 		</div>
		 	<?php } ?>
		 	<?php if(isset($_GET['q15'])){ ?>
	 		<div class="col-mod-4">
	 			<span>If you wish to be contacted, please click on the following box.:</span>
	 			<p><?php echo $_GET['q15'];?></p>
	 		</div>
		 	<?php } ?>
 	</div>
 	<div class="Back-btn">
 		<a href="<?php echo home_url();?>">Back ></a>
 	</div>
    <?php }?>

</div>	
<script>
	jQuery(document).ready(function(){
	    var uri = window.location.toString();
	    var lang = '<?php echo $lang; ?>';
	    if(lang == "es"){
	    	var clean_uri = uri.substring(0, uri.indexOf("&"));
		    window.history.replaceState({}, document.title, clean_uri);
	    }else{
		    if(uri.indexOf("?") > 0){
		        var clean_uri = uri.substring(0, uri.indexOf("?"));
		        window.history.replaceState({}, document.title, clean_uri);
		    }
		}
	});
</script>
<?php get_footer();
?>