<?php require_once('process.php'); ?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,600,300,700, Cardo, Gentium+Book+Basic:400,400italic,700,700italic' rel='stylesheet' type='text/css'>

	<title>CSS Visual Report</title>
</head>
<body>

	<header>
		<section class="wrapper nobg">
			<h1>CSS Visual Report</h1>
			<h2><span>Upload a css file and see what it contains</span></h2>
		</section>

	</header>
	
	<section class="wrapper">
		<article class="main">
			<form id="upload_form" enctype="multipart/form-data" method="post" action="">
				<h1 id="label"><label for="css_file">Please select a css file</label></h1>
				
				<div id="fileSelect">
					<input type="file" name="css_file" id="css_file" />
				</div>
				
				<div id="submitButton">
					<input type="submit" value="upload" name="upload" id="upload" />
				</div>
				
			</form>
		</article>
		
		<?php 
			if(isset($_POST['upload'])): 
			$process = new process();
			echo $process->upload();
			$css = $process->parse();
		?>
		
		<div class="report">
			<h1 id="reportHeadline">Your CSS file contains:</h1>
			
			<div id="reportColors">
				<h3>h1 Colors:</h3>
				<h1 style="color:<?php echo $css['h1']['color']; ?>">This is a sample of color <?php echo $css['h1']['color']; ?> </h1>
			</div>
			
			<div id="reportFonts">
				<h3>Fonts:</h3>
					<?php 
						$fonts = $process->findElements('font-family');
						
						foreach($fonts as $font){
							echo "<p style='font-family:" . $font . "'>This is a sample of " . $font . "</p>";
						}
					?>
			</div>
			
			<div id="">
				<h3>Fonts sizes:</h3>
					<?php 
						$fontSizes = $process->findElements('font-size');
						
						foreach($fontSizes as $fontSize){
							echo "<p style='font-size:" . $fontSize . "'>This is a sample of " . $fontSize . "</p>";
						}
					?>
			</div>
		</div>
		
		<?php endif ?>

	</section>
	
</body>
</html>