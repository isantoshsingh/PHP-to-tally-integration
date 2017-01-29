<?php    

if(count($_POST)) {
	
	$group_name = strtoupper($_POST['group_name']);
	$item_name = $_POST['item_name'];
	$opening_balance = $_POST['opening_balance'];
	$opening_value = $_POST['opening_value'];
	$opening_rate = $opening_value * $opening_balance;
	
	$res_str =<<<XML
	<ENVELOPE>
<HEADER>
<TALLYREQUEST>Import Data</TALLYREQUEST>
</HEADER>
<BODY>
<IMPORTDATA>
<REQUESTDESC>
<REPORTNAME>All Masters</REPORTNAME>
</REQUESTDESC>
<REQUESTDATA>

<!-- Create Stock Group named "$group_name" -->
<TALLYMESSAGE xmlns:UDF="TallyUDF">
<STOCKGROUP NAME="{$group_name}" ACTION="Create">
<NAME.LIST>
<NAME>{$group_name}</NAME>
</NAME.LIST>
<PARENT/>
<ISADDABLE>Yes</ISADDABLE>
</STOCKGROUP>
</TALLYMESSAGE>

<!-- Create Stock Item named "$item_name" -->
<TALLYMESSAGE xmlns:UDF="TallyUDF">
<STOCKITEM NAME="{$item_name}" ACTION="Create">
<NAME.LIST>
<NAME>{$item_name}</NAME>
</NAME.LIST>
<PARENT>{$group_name}</PARENT>
<BASEUNITS>NOS</BASEUNITS>
<OPENINGBALANCE>{$opening_balance} NOS</OPENINGBALANCE>
<OPENINGVALUE>{$opening_rate}</OPENINGVALUE>
<BATCHALLOCATIONS.LIST>
<NAME>Primary Batch</NAME>
<BATCHNAME>Primary Batch</BATCHNAME>
<GODOWNNAME>Main Location</GODOWNNAME>
<MFDON>20170120</MFDON>
<OPENINGBALANCE>0.000 NOS</OPENINGBALANCE>
<OPENINGVALUE>0.000</OPENINGVALUE>
<OPENINGRATE>0.000/NOS</OPENINGRATE>
</BATCHALLOCATIONS.LIST>
</STOCKITEM>
</TALLYMESSAGE>
</REQUESTDATA>
</IMPORTDATA>
</BODY>
</ENVELOPE>
XML;
	
	//var_dump($res_str);die;
	
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
		
		if(curl_errno($ch)){
			var_dump($data);
		} else {
			$msg = 'Data successfully inserted into tally.';
		}
        curl_close($ch);
		
		
}

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
			<h3>Insert data into Tally <div class="pull-right"><a href="export.php">Item List</a></div></h3>
			<hr>
			
			<form class="form-horizontal" method="post" action="">
			  <div class="form-group">
				<label for="group-name" class="col-sm-4 control-label">Stock Group name</label>
				<div class="col-sm-6">
				  <input type="text" class="form-control" id="group-name" placeholder="Stock Group name" name="group_name" required>
				</div>
			  </div>
			  <div class="form-group">
				<label for="item-name" class="col-sm-4 control-label">Stock Item name</label>
				<div class="col-sm-6">
				  <input type="text" class="form-control" id="item-name" placeholder="Stock Item name" name="item_name" required>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="opening_balance" class="col-sm-4 control-label">Quantity</label>
				<div class="col-sm-6">
				  <input type="text" class="form-control" id="opening_balance" placeholder="Item Quantity" name="opening_balance" required>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label for="opening_value" class="col-sm-4 control-label">Unit Price</label>
				<div class="col-sm-6">
				  <input type="text" class="form-control" id="opening_value" placeholder="Item Unit Price" name="opening_value" required>
				</div>
			  </div>
			  
			  
			  <div class="form-group">
				<div class="col-sm-offset-4 col-sm-6">
				  <button type="submit" class="btn btn-primary">Insert</button>
				</div>
			  </div>
			</form>
		</div>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>