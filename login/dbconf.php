<?php
//DATABASE CONNECTION VARIABLES
$host = 'localhost'; // Host name
$username = 'root'; // Mysql username
$password = 'cwycjz321@QWER'; // Mysql password
$db_name = 'atrigenome'; // Database name

$tbl_prefix = ""; //Prefix for all database tables
$tbl_members = $tbl_prefix."members";
$tbl_memberinfo = $tbl_prefix."member_info";
$tbl_roles = $tbl_prefix."roles";
$tbl_member_roles = $tbl_prefix."member_roles";
$tbl_attempts = $tbl_prefix."login_attempts";
$tbl_deleted = $tbl_prefix."deleted_members";
$tbl_tokens = $tbl_prefix."tokens";
$tbl_cookies = $tbl_prefix."cookies";
$tbl_app_config = $tbl_prefix."app_config";
$tbl_mail_log = $tbl_prefix."mail_log";
$tbl_member_jail = $tbl_prefix."member_jail";
$tbl_permissions = $tbl_prefix."permissions";
$tbl_role_permissions = $tbl_prefix."role_permissions";


// custom configure
$elfinderFolder_HOME="/disk/files";
$elfinderFolder_PUBLIC="/disk/public";
$elfinderFolder_S3DISK="/S3DISK/files";
$ssh_account="atri";
$ssh_password="Nchc@org.tw@1234";

