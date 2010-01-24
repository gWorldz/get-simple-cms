<?php 
/****************************************************
*
* @File: 		archive.php
* @Package:	GetSimple
* @Action:	Displays and starts the website archives 	
*
*****************************************************/

// Setup inclusions
$load['plugin'] = true;

// Relative
$relative = '../';

// Include common.php
include('inc/common.php');

// Variable Settings
$userid = login_cookie_check();
$table = '';

// if zip.php exists, delete it	
if (file_exists('../zip.php'))
{
	unlink('../zip.php');
}

// if a backup needs to be created
if(isset($_GET['do']))
{
	$file = "inc/zip-files.php";
	$newfile = "../zip.php";
	copy($file, $newfile);
	exec_action('archive-backup');
	header('Location: ../zip.php');	
}

// if a backup has just been created
if(isset($_GET['done'])) 
{
	if (file_exists("../zip.php")) 
	{
		unlink("../zip.php");
		
		$success = $i18n['SUCC_WEB_ARCHIVE'];
	}
}
?> 

<?php get_template('header', cl($SITENAME).' &raquo; '.$i18n['BAK_MANAGEMENT'].' &raquo; '.$i18n['WEBSITE_ARCHIVES']); ?>
	
	<h1><a href="<?php echo $SITEURL; ?>" target="_blank" ><?php echo cl($SITENAME); ?></a> <span>&raquo;</span> <?php echo $i18n['BAK_MANAGEMENT']; ?> <span>&raquo;</span> <?php echo $i18n['WEBSITE_ARCHIVES']; ?></h1>
	
	<?php include('template/include-nav.php'); ?>
	<?php 
	if (isset($success)) {
		echo '<div class="updated">'.$success.'</div>';
	} elseif (isset($err)) {
		echo '<div class="error"><b>'.$i18n['ERROR'].':</b> '.$err.'</div>';
	} elseif (@$_GET['upd'] == 'del-success' ) {
		echo '<div class="updated">'.$i18n['SUCC_WEB_ARC_DEL'].': <b>'.$_GET['id'].'</b></div>';
	}
	?>

	<div class="bodycontent">
	
	<div id="maincontent">
		<div class="main" >
		<label><?php echo $i18n['WEBSITE_ARCHIVES'];?></label>
		<div class="edit-nav" >
		<a id="waittrigger" href="archive.php?do" accesskey="c" title="<?php echo $i18n['CREATE_NEW_ARC'];?>" ><?php echo $i18n['ASK_CREATE_ARC'];?></a>
		<div class="clear"></div></div>
		<p style="display:none" id="waiting" ><?php echo $i18n['CREATE_ARC_WAIT'];?></p>
		<table class="highlight paginate">	
		<?php
			$count="0";
			$path = tsl("../backups/zip/");
			
			$filenames = getFiles($path);

			natsort($filenames);
			rsort($filenames);
			
			foreach ($filenames as $file) {
				if($file != "." && $file != ".." && $file != ".htaccess" ) {
					$timestamp = explode('_', $file);
					$name = shtDate($timestamp[0]);
					clearstatcache();
					$ss = @stat($path . $file);
					$size = fSize($ss['size']);
					echo '<tr>
							<td><a title="Download Archive: '. $name .'?" href="'. $path . $file .'">'.$name .'</a></td>
							<td style="width:70px;text-align:right;" ><span>'.$size.'</span></td>
							<td class="delete" ><a class="delconfirm" title="Delete Archive: '. $name .'?" href="deletefile.php?zip='. $file .'">X</a></td>
						  </tr>';
					$count++;
				}
			}

		?>
		</table>
		<p><em><b><?php echo $count; ?></b> <?php echo $i18n['TOTAL_ARCHIVES'];?></em></p>
		</div>
	</div>
	
	<div id="sidebar" >
		<?php include('template/sidebar-backups.php'); ?>
	</div>

	
	<div class="clear"></div>
	</div>
<?php get_template('footer'); ?>