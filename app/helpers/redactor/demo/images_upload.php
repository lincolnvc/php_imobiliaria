<!DOCTYPE html>
<html>
<head>	
	<title>Images upload</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->	
	
	<link rel="stylesheet" type="text/css" href="../css/style.css" /> 		
	<script type="text/javascript" src="http://127.0.0.1/redactor764/lib/jquery-1.7.1.min.js"></script>	
	<link rel="stylesheet" href="http://127.0.0.1/redactor764/redactor/css/redactor.css" />
	<script src="http://127.0.0.1/redactor764/redactor/redactor.min.js"></script>
</head>
<body>
	<div id="page">
	<textarea id="redactor" class="redactor" name="content" style="height: 460px;">
			<h2>Hello and Welcome</h2>
	</textarea>	
	</div>						
</body>

	<script type="text/javascript">
	$(document).ready(function()
	{
		//$('.redactor').redactor();
		$('.redactor').redactor({ imageUpload: 'http://127.0.0.1/redactor764/demo/scripts/image_upload.php' });
	});
	</script>	
	

</html>