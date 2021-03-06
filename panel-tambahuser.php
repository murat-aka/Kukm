<?php
// required file
require 'sysconfig.inc.php';
require SIMBIO_BASE_DIR.'simbio_DB/simbio_dbop.inc.php';
include "nav_panel.php";

if (isset($_POST['searchData'])) {
	$kopnama = $_POST['koperasi'];
    $nama = $_POST['nama'];
    $search_limit = "";
	if ($kopnama <>"") {
		$search_limit = ' k.idkoperasi ='. $kopnama ;
    }
    if ($nama <>"") {
        if ($search_limit <>"") {
            $search_limit .= ' AND (u.nama = "%'. $nama .'%" OR u.login = "%'. $nama . '%")';
        } else {
            $search_limit =' u.nama = "%'. $nama .'%" OR u.login = "%'. $nama . '%"';
        }
    }
		// get record
		$sql_text = "SELECT u.*, k.nama FROM user as u ";
		$sql_text .= " LEFT JOIN koperasi as k ON u.idkoperasi = k.idkoperasi ";
		if (isset($search_limit)) {
			$sql_text .= "WHERE ". $search_limit;
		}
		$q_koperasi = $dbs->query($sql_text);
		$reckoperasi = $q_koperasi->fetch_assoc();
}

if (isset($_POST['saveUser'])) {

    $sql_op = new simbio_dbop($dbs);

	if (isset($_POST['updatenid'])) {
		$iduser = $_POST['updatenid'];
	}
	$data['nama'] = $_POST['nama'];
	$data['koperasi_idkoperasi'] = $_POST['koperasi_idkoperasi'];
	$data['divisi']=$_POST['divisi'];
	$data['telp']=$_POST['telp'];
	$data['email']=$_POST['email'];
	$data['fax']=$_POST['fax'];
    if (!isset($iduser)) {
        $data['login']=$_POST['login'];
        $data['group_idgroup']=isset($_POST['group_idgroup']) ? $_POST['group_idgroup'] : 0;
//        $data['validasi']=$_POST['validasi'];
        if ($_POST['password'] <> "") {
            $data['password']=$_POST['password'];
            if ($data['password'] <> $_POST['new_confirm']) {
                $nomatch = true;
                $message = 'Password tidak sama. Ulangi lagi';
                $recNon = $data;
            } else {
                $nomatch = false;
            }
        }
    }

	if (isset($iduser) AND $iduser <> 0) {
        if ((isset($nomatch) AND !$nomatch)) {
            $update = $sql_op->update('user', $data, 'iduser ='.$iduser);
            if ($update) {
                $message = 'Data User berhasil diperbaiki.';
            } else {
                $message = 'Data User GAGAL diperbaiki. '.$update->error;
            }
        }
	} else {
        if ((isset($nomatch) AND !$nomatch)) {
            $insert = $sql_op->insert('user', $data);
            if ($insert) {
                $message = 'Data User berhasil disimpan.';
            } else {
                $message = 'Data User GAGAL disimpan. '.$sql_op->error;
                //die($insert->error);
            }
        }
	}

}

if (isset($_GET['nid']) AND $_GET['nid'] <> "") {
	// get record
	$iduser = $_GET['nid'];
	$sql_text = "SELECT * FROM user WHERE iduser =". $iduser;
	$q_user = $dbs->query($sql_text);
	$recNon = $q_user->fetch_assoc();
}

// start the output buffering for main content
ob_start();

session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="content-language" content="en" />
	<meta name="robots" content="noindex,nofollow" />
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/reset.css" /> <!-- RESET -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/main.css" /> <!-- MAIN STYLE SHEET -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/2col.css" title="2col" /> <!-- DEFAULT: 2 COLUMNS -->
	<link rel="alternate stylesheet" media="screen,projection" type="text/css" href="css/1col.css" title="1col" /> <!-- ALTERNATE: 1 COLUMN -->
	<!--[if lte IE 6]><link rel="stylesheet" media="screen,projection" type="text/css" href="css/main-ie6.css" /><![endif]--> <!-- MSIE6 -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/style.css" /> <!-- GRAPHIC THEME -->
	<link rel="stylesheet" media="screen,projection" type="text/css" href="css/mystyle.css" /> <!-- WRITE YOUR CSS CODE HERE -->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/switcher.js"></script>
	<script type="text/javascript" src="js/toggle.js"></script>
	<script type="text/javascript" src="js/ui.core.js"></script>
	<script type="text/javascript" src="js/ui.tabs.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$(".tabs > ul").tabs();
	});
	</script>
	<title>Kementerian KUKM - JKUK</title>
</head>

<body>
<?php
if (isset($message) AND $message<>"") {
    utility::jsAlert($message);
}
?>
<div id="main">

	<!-- Tray -->
	<div id="tray" class="box">

		<p class="f-left box">

			<!-- Switcher -->
			<span class="f-left" id="switcher">
				<a href="#" rel="1col" class="styleswitch ico-col1" title="Display one column"><img src="design/switcher-1col.gif" alt="1 Column" /></a>
				<a href="#" rel="2col" class="styleswitch ico-col2" title="Display two columns"><img src="design/switcher-2col.gif" alt="2 Columns" /></a>
			</span>

			Project: <strong>Kementerian KUKM</strong>

		</p>

		<p class="f-right">User: <strong><a href="#"><?php echo isset($_SESSION['userName']) ? $_SESSION['userName'] : "None";?></a></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><a href="index.php?login=" id="logout">Log out</a></strong></p>

	</div> <!--  /tray -->

	<hr class="noscreen" />

	<!-- Menu -->
	<div id="menu" class="box">

		<ul class="box f-right">
			<li><a href="#"><span><strong>Visit Site &raquo;</strong></span></a></li>
		</ul>

		<ul class="box">

<?php
echo menutop(3);
?>
		</ul>

	</div> <!-- /header -->

	<hr class="noscreen" />

	<!-- Columns -->
	<div id="cols" class="box">

		<!-- Aside (Left Column) -->
		<div id="aside" class="box">

			<div class="padding box">

				<!-- Logo (Max. width = 200px) -->
				<p id="logo"><a href="#"><img src="tmp/logo.gif" alt="Our logo" title="Visit Site" /></a></p>

			</div> <!-- /padding -->

<?php
echo navigation(1);
?>

		</div> <!-- /aside -->

		<hr class="noscreen" />

		<!-- Content (Right Column) -->
		<div id="content" class="box">

			<h1>Panel</h1>

			<!-- Headings -->
			<h3 class="tit"><?php echo isset($iduser)? "Edit" : "Tambah"; ?> User</h3>
<form id='form_user' method=post>

<table class="nostyle">
  <tr>
    <td>Kode Login</td>
    <td><input type="text" size="40" name="login" value="<?php echo isset($recNon['login']) ? $recNon['login'] : ""; ?>" class="input-text-02" <?php echo isset($iduser)? "disabled" : ""; ?> /></td>
  </tr>
  <tr>
    <td>Nama</td>
    <td><input type="text" size="40" name="nama" value="<?php echo isset($recNon['nama']) ? $recNon['nama'] : ""; ?>" class="input-text-02" /></td>
  </tr>
  <tr>
    <td>Koperasi</td>
<?php
	$sql_text = "SELECT idkoperasi, nama from koperasi ORDER BY nama";
	$option = $dbs->query($sql_text);
    if ($_SESSION['group'] == 1) {
    	echo '<td><select id="jenis" name="koperasi_idkoperasi" class="input-text-02">';
    } else {
        echo '<td><input type="hidden" name="koperasi_idkoperasi" value="'.$_SESSION['koperasi'].'">';
    	echo '<select id="jenis"  class="input-text-02" disabled>';
    }
	echo '<option value="0">--- Pilih Koperasi ---</option>';
    if (!isset($recNon['koperasi_idkoperasi'])) {
        $recNon['koperasi_idkoperasi'] = 0;
    }
    
	while ($choice = $option->fetch_assoc()) {
		if ($choice['idkoperasi'] == $recNon['koperasi_idkoperasi'] OR $choice['idkoperasi'] == $_SESSION['koperasi']) {
			echo '<option value="'.$choice['idkoperasi'].'" SELECTED >'.$choice['nama'].'</option>';
		} else {
			echo '<option value="'.$choice['idkoperasi'].'">'.$choice['nama'].'</option>';
		}
	}
	unset ($choice);
	echo '</select></td>';
?>
  </tr>
  <tr>
    <td>Email</td>
    <td><input type="text" size="40" name="email" value="<?php echo isset($recNon['email']) ? $recNon['email'] : ""; ?>" class="input-text-02" /></td>
  </tr>
  <tr>
    <td>Divisi / Bagian</td>
    <td><input type="text" size="40" name="divisi" value="<?php echo isset($recNon['divisi']) ? $recNon['divisi'] : ""; ?>" class="input-text-02" /></td>
  </tr>
  <tr>
    <td>Telpon</td>
    <td><input type="text" size="40" name="telp" value="<?php echo isset($recNon['telp']) ? $recNon['telp'] : ""; ?>" class="input-text-02" /></td>
  </tr>
  <tr>
    <td>Fax</td>
    <td><input type="text" size="40" name="fax" value="<?php echo isset($recNon['fax']) ? $recNon['fax'] : ""; ?>" class="input-text-02" /></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><input type="password" size="40" name="password" value="" class="input-text-02" pattern="^.{8}.*$" /> * minimal 8 karakter</td>
  </tr>
  <tr>
    <td>Konfirmasi Password</td>
    <td><input type="password" size="40" name="new_confirm" value="" class="input-text-02" pattern="^.{8}.*$" /></td>
  </tr>
<?php
if ($_SESSION['group'] == 1) {
  echo '<tr>';
  echo '  <td>Group</td>';

	$sql_text = "SELECT * from `group` ORDER BY `idgroup`";
	$option = $dbs->query($sql_text);
	echo '<td><select id="group" name="group_idgroup" class="input-text-02">"';
	echo '<option value="">--- Pilih Group ---</option>';
	while ($choice = $option->fetch_assoc()) {
		if ($choice['idgroup'] == $recNon['group_idgroup']) {
			echo '<option value="'.$choice['idgroup'].'" SELECTED >'.$choice['group'].'</option>';
		} else {
			echo '<option value="'.$choice['idgroup'].'">'.$choice['group'].'</option>';
		}
	}
	unset ($choice);
	echo '</select></td>';
    echo '</tr>';
}
?>
  <tr>
	<td colspan="2" class="t-right"><input type="submit" name="saveUser" class="input-submit" value="Submit" /></td>
  </tr>
</table>

<?php
if (isset($iduser)) {
    echo '<input type="hidden" name="updatenid" value="'.$iduser.'"/>';
}
?>
</form>

		</div> <!-- /content -->

	</div> <!-- /cols -->

	<hr class="noscreen" />

	<!-- Footer -->
	<div id="footer" class="box">

		<p class="f-left">&copy; 2012 <a href="#">Kementerian Koperasi dan UKM</a>, All Rights Reserved &reg;</p>

		<p class="f-right">Templates by <a href="http://www.adminizio.com/">Adminizio</a></p>

	</div> <!-- /footer -->

</div> <!-- /main -->

</body>
</html>
