<?php
$title = "Standard User Page";
$userrole = "Standard User"; // Allow only logged in users
include "login/misc/pagehead.php";
include "dataFunction.php";
# Preload Value
date_default_timezone_set('Asia/Taipei'); 
$authorName=$_SESSION['username'];
$today  = date("YmdHis")."_".rand();
$encodeKey=base64_encode($today);
?>
</head>
<body>
  <?php require 'login/misc/pullnav.php'; ?>
    <div class="container">
		<?php
		##### Main Start #######
		define('MODULE_FILE', true);
		$module_name=$_GET["name"];
		$module_name = addslashes(trim($module_name));
		$modpath = "modules/$module_name/index.php";
		if (file_exists($modpath)) {
		 include($modpath);
		}
		##### Main Emd #######
		?>
    </div>
</body>
</html>
