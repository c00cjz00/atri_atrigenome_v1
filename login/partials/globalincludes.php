<?php
// Global Includes
/*echo '<script src="'.$this->base_url.'/vendor/components/jquery/jquery.min.js" type="application/javascript"></script>
      <script src="'.$this->base_url.'/vendor/components/bootstrap/js/bootstrap.min.js" type="application/javascript"></script>
      <link href="'.$this->base_url.'/vendor/components/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
      <link href="'.$this->base_url.'/login/css/main.css" rel="stylesheet" media="screen">
	  <link href="'.$this->base_url.'/user/css/my.css" rel="stylesheet" media="screen">';*/

if (!isset($_GET["d_op"]) || ($_GET["d_op"]!="pipelineReport")){
?>
	<!-- default -->
	<script src="https://code.jquery.com/jquery-3.7.1.js" type="application/javascript"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="application/javascript"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<!-- ace styles -->
	<link href="/login/css/main.css" rel="stylesheet" media="screen">
	<!-- my styles -->
	<link href="/assets/css/my.css" rel="stylesheet" media="screen">
	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="/assets/font-awesome-4.7.0/css/font-awesome.min.css" />
	<!-- text fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">

<?php
}
?>