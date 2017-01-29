<?php

$res_str =<<<XML
<ENVELOPE>
<HEADER>
<TALLYREQUEST>Export Data</TALLYREQUEST>
</HEADER>
<BODY>
<EXPORTDATA>
<REQUESTDESC>
<REPORTNAME>stock category summary</REPORTNAME>
</REQUESTDESC>
</EXPORTDATA>
</BODY>
</ENVELOPE>
XML;

$url = "http://localhost:9000/";

        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
// Following line is compulsary to add as it is:
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "xmlRequest=" . $res_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $data = curl_exec($ch);
		//echo '<pre>';
		//var_dump($data);

        curl_close($ch);
		// get the xml object 
    $object = simplexml_load_string( $data );
	
	//print_r($object);
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Tally</title>

    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
	
	<div class="container">
	
		<div class="col-md-6 col-md-offset-3" style="margin-top:20px">
			<?php if(isset($msg) && $msg) :?>
			<div class="alert alert-success" role="alert"><?=$msg?></div>
			<?php endif;?>
			
			<h3>Tally Item List <div class="pull-right"><a href="item.php">Add item</a></div></h3>
			<hr>
			
			<table class="table table-bodered">
				<tr>
					<th>Item Name</th>
					<th>Item Quantity</th>
					<th>Item Rate</th>
				</tr>
				<?php $i = 0;?>
				<?php foreach($object->DSPACCNAME as $value) :?>
				<tr>
					<td><?=$value->DSPDISPNAME?></td>
					<td><?=$object->DSPSTKINFO[$i]->DSPSTKCL->DSPCLQTY?></td>
					<td><?=$object->DSPSTKINFO[$i]->DSPSTKCL->DSPCLRATE?></td>
				</tr>
				<?php $i++;?>
				<?php endforeach;?>
			</table>

		</div>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>