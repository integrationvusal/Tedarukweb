<?php if(!defined("_VALID_PHP")) die('Direct access to this location is not allowed.');
	
    $_SESSION = array();
    unset($_SESSION['ses_adm_id']);
    unset($_SESSION['ses_adm_reg_date']);
	session_destroy();
    
?>
<script type="text/javascript">
	window.location="index.php";
</script>
