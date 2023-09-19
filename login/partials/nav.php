<nav class="navbar navbar-default">
    <div class="container-fluid">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapsed" aria-expanded="false">
        <span class="glyphicon glyphicon-menu-hamburger"></span>
      </button>

<?php
//SITE LOGO (IF SET) OR SITE NAME
if (str_replace(' ', '', $this->mainlogo) == '') {
    //No logo, just renders site name as link
    echo '<ul class="nav navbar-nav navbar-left"><li class="sitetitle"><a class="navbar-brand" href="'.$this->base_url.'">'.$this->site_name.'</a></li></ul>';
} else {
    //Site main logo as link
    echo '<ul class="nav navbar-nav navbar-left"><li class="mainlogo"><a class="navbar-brand" href="'.$this->base_url.'"><img src="'.$this->mainlogo.'" height="36px"></a></li></ul>';
}
?>
<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="navbar-collapsed">

<!-- BOOTSTRAP NAV LINKS GO HERE. USE <li> items with <a> links inside of <ul> -->


<?php
// SIGN IN / USER SETTINGS BUTTON
$auth = new PHPLogin\AuthorizationHandler;

// Pulls either username or first/last name (if filled out)
if ($auth->isLoggedIn()) {
    $usr = PHPLogin\ProfileData::pullUserFields($_SESSION['uid'], array('firstname', 'lastname'));
    if ((is_array($usr)) && (array_key_exists('firstname', $usr) && array_key_exists('lastname', $usr))) {
        $user = $usr['firstname']. ' ' .$usr['lastname'];
    } else {
        $user = $_SESSION['username'];
    } ?>
	 <ul class="nav navbar-nav"> 
	  <li><a href="/elfinder.php" target="_blank"><i class="fa fa-folder-open-o" aria-hidden="true"></i> 檔案總管</a></li> 
	  <li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-arrows-alt" aria-hidden="true"></i> 工作派送 <b class="caret"></b></a>
	   <ul class="dropdown-menu multi-level">
		<li><a href="/modules.php?name=table&d_op=sra_download">1a. SRA download</a></li>
		<li><a href="/modules.php?name=table&d_op=fastq_qc">1b. 定序品質分析 (fastq.gz)</a></li>
		<!--li><a href="/modules.php?name=pipeline&d_op=08-pipeline_fastq">02. 基因序列重組</a></li>
		<li><a href="/modules.php?name=pipeline&d_op=09-pipeline_fastq">03. 病原抗藥性比對</a></li>
		<li><a href="/modules.php?name=pipeline&d_op=10-pipeline_snvfasta">04. 血清型分析(sistr)</a></li>
		<li><a href="/modules.php?name=pipeline&d_op=11-pipeline_fastq">05. De Novo 組裝(unicyler) + 重組血清型分析 (sistr)</a></li>
		<li><a href="/modules.php?name=pipeline&d_op=12-pipeline_fastq">06. 血清型分析 (SeqSero)</a></li-->
	   </ul> 
	  </li>

	  <li><a href="/modules.php?name=table&d_op=pipeline_table"><i class="fa fa-check-square-o" aria-hidden="true"></i> 派送結果</a></li>
	  <li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa  fa-file-text-o" aria-hidden="true"></i> 表單輸出 <b class="caret"></b></a>
	   <ul class="dropdown-menu multi-level">
		<li><a href="/modules.php?name=table&d_op=pipeline_report_single">01. 序列品質報告 (單一樣本)</a></li>
		<li><a href="/modules.php?name=table&d_op=pipeline_report_multiple">02. 序列品質報告 (實驗樣本摘要)</a></li>
		<!--li><a href="/modules.php?name=pipeline&d_op=pipelineTable_card">03. 抗藥性比對報告 (CARD v3.0)</a></li>
		<li><a href="/modules.php?name=pipeline&d_op=pipelineTable_sistr">04. 重組血清型分析報告 (sistr)</a></li>
		<li><a href="/modules.php?name=pipeline&d_op=pipelineTable_seqsero">05. 定序血清型分析報告 (Seqsero)</a></li-->
	   </ul> 
	  </li>
	  <li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-table" aria-hidden="true"></i> Databases <b class="caret"></b></a>
	   <ul class="dropdown-menu multi-level">
		<li><a href="/modules.php?name=table&d_op=card_table">01. 抗藥性資料庫 (CARD v3.0) [查詢]</a></li>
		<!--li><a href="/datatables/index.php?d_op=dtEditor&d_table=atri_TB04">02. 最小抑菌濃度 [輸入]</a></li>
		<li><a href="/datatables/index.php?d_op=dtEditor&d_table=atri_TB05">03. 抗藥性判定標準 [輸入]</a></li>
		<li><a href="/datatables/index.php?d_op=dtEditor&d_table=atri_TB06">04. 各國抗效性趨勢 [輸入]</a></li-->
	   </ul>
	  </li>  
	  <li><a href="/mycpu.php"><i class="fa fa-folder-open-o" aria-hidden="true"></i> Monitor</a></li>
	 </ul>
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <?php echo $user; ?>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                <li><a href="<?php echo $this->base_url; ?>/user/accountedit.php">Account Settings</a></li>
                <li role="separator" class="divider"></li>

                <!-- Superadmin Controls -->
                <?php if ($auth->isSuperAdmin()): ?>
                  <li><a href="<?php echo $this->base_url; ?>/admin/config.php">Edit Site Config</a></li>
                  <li><a href="<?php echo $this->base_url; ?>/admin/permissions.php">Manage Permissions</a></li>
                  <li role="separator" class="divider"></li>
                <?php endif; ?>
                <!-- Admin Controls -->
                <?php if ($auth->isAdmin()): ?>
                  <li><a href="<?php echo $this->base_url; ?>/admin/users.php">Manage Users</a></li>
                  <li><a href="<?php echo $this->base_url; ?>/admin/roles.php">Manage Roles</a></li>
                  <li><a href="<?php echo $this->base_url; ?>/admin/mail.php">Mail Log</a></li>
                  <li role="separator" class="divider"></li>
                <?php endif; ?>

                <li><a href="<?php echo $this->base_url; ?>/login/logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>

<?php
} else {
        //User not logged in?>
    <ul class="nav navbar-nav navbar-right">
    <li class="dropdown"><a href="<?php echo $this->base_url; ?>/login/index.php" role="button" aria-haspopup="false" aria-expanded="false">Sign In
    </a>
    </li>
    </ul>

<?php
    };

?>

</div><!-- /.navbar-collapse -->
</div>
</div>
</nav>
