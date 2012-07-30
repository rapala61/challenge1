<!DOCTYPE html>
<?php include_once('functions.php')?>
<html>
	<head>
		<title>English number Translator</title>
		<meta name="description" content="Challenges for PHP Developer position" />
	</head>
	<body>
		<div id="candidate_info"></div>
		<div id="challenges_canvas">	
			<div id="description"></div>
			<div id="form">
				<form action="<?php echo $PHP_SELF; ?>" method="post" accept-charset="utf-8">
					<p>English written number</p>
					<input type="text" size="80" name="string" value="<?php echo $_POST['string']; ?>"/><input type="submit" value="Translate" />
				</form>
				<div id="results">
					<?php
					
					$obj = new Translator;
					$commaSeparatedNums = $obj->splitString($_POST['string']);
					$translation = $obj->translate($commaSeparatedNums);
					echo implode(", ", $translation);

					?>
				</div>
			</div>
		</div>
	</body>
	
	
</html>



