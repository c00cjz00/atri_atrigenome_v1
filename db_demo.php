<?php
$title = "Standard User Page";
$userrole = "Standard User"; // Allow only logged in users
include "login/misc/pagehead.php";
?>
</head>
<body>
  <?php require 'login/misc/pullnav.php'; ?>
    <div class="container">
        <h2>Standard User Page</h2>
        <p>Hello, <?=$_SESSION["username"]?>!</p>
        <p>This page requires a Standard User to be logged in</p>
    </div>
    <div class="container">
	<?php
	## OBJECT
	$db = new PHPLogin\DbConn;
	
	// ## SELECT
	// try {
	 // $err = null;
	 // $id="2";	
	 // $sql = "SELECT * FROM  role_permissions  WHERE id <= $id";
	 // $stmt = $db->conn->prepare($sql);
	 // $stmt->execute();
	 // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	 // unset($stmt);
	// } catch (PDOException $e) {
	 // $err = "Error: " . $e->getMessage();
	// }
	// #Determines returned value ('true' or error code)
	// $resp = ($err == null) ? true : $err;
	// echo $resp."<Br>";
	// if ($resp==1) {
	 // #print_r($result);	
	 // echo "TOTAL: ".count($result)."<br>";
	 // foreach($result as $row){
	  // foreach($row as $key => $value){
	   // echo $key." : ".$value."<br />";
	  // }
	 // }
	// }

	## INSERT
	// try {		
	 // $err = null;
	 // $sql="INSERT INTO atri_card_demo (id, aroID , name, namespace, is_a, relationship, def) VALUES (NULL, 'a1' , '1a', '1a', 'a', 'a', 'a')";
	 // $stmt = $db->conn->prepare($sql);	 
	 // $stmt->execute();
     // $last_id = $db->conn->lastInsertId();
	 
	 // unset($stmt);
	// } catch (PDOException $e) {
	 // $err = "Error: " . $e->getMessage();
	// }
	// #Determines returned value ('true' or error code)
	// $resp = ($err == null) ? true : $err;
	// echo $resp ."<br>";
	// echo  $last_id."<br>";

	// ## UPDATE
	// try {
	 // $err = null;
	 // $sql="UPDATE atri_card_demo SET aroID = 'abc'  WHERE id = '11123'";
	 // $stmt = $db->conn->prepare($sql);
	 // $stmt->execute();
	 // unset($stmt);
	// } catch (PDOException $e) {
	 // $err = 'Error: ' . $e->getMessage();
	// }
	// #Determines returned value ('true' or error code)	
	// $resp = ($err == null) ? true : $err;
	// echo $resp ;

	## DELETE
	// try {
	 // $err = null;
	 // $sql="delete from atri_card_demo WHERE id = '11123'";
	 // $stmt = $db->conn->prepare($sql);
	 // $stmt->execute();
	 // unset($stmt);
	// } catch (PDOException $d) {
	 // $err = 'Error: ' . $d->getMessage();
	// }
	// $resp = ($err == '') ? true : $err;
	// echo $resp;


	// ## Class
	// $obj = new PHPLogin\UserHandler;
	// $email="summerhill001@gmail.com";
	// $result = $obj->pullUserByEmail($email);
	// print_r($result);

	// #$userarr = array(array('id'=>$newid, 'username'=>$newuser, 'email'=>$newemail, 'pw'=>$pw1));
	// #$response = $obj->createUser($userarr);

	?>



	</div>



</body>
</html>

