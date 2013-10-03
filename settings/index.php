<?php # Settings Home Page
$metatitle = 'Settings';
$returnurl = '/login/?returnpage=settings/';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Settings</h1>
<?php
if ( ($_SESSION['role']=='O') || ($_SESSION['role']=='A') )
{
# Help Button
$helphref = 'settings-manage.html';
$helptitle = 'Help with Managing Account Settings';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
}
else
{
# Help Button
$helphref = 'settings.html';
$helptitle = 'Help with Your Account Settings';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
}
?>
</div>

<!-- Main Content -->

<?php
if (isset($_REQUEST['action']))
{
	if ($_REQUEST['action']=='accountadded')
	{
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Your new account was successfully added to the system.</p>
			</div>
		</div>
	</div>
</div>';
	}
}
?>  

<!--  boxes-form-->
<form action="/settings/subaccount/edit/" method="post" class="boxes-form">
<fieldset>

<!--  boxes-container-->

<div class="boxes-container">

<!-- 1st Row of Boxes -->

		<div class="section-boxes">
			<div class="boxes-holder">
				<div class="boxes-frame">
					<!-- My Info Box -->
					<div class="box">
						<div class="holder">
							<div class="frame">
								<h2>My Info</h2>
								<div class="text-holder">
									<a href="/settings/myinfo/">Update your personal information in the system</a>
								</div>
							</div>
						</div>
					</div>
					<!-- People Box -->
					<div class="box">
						<div class="holder">
							<div class="frame">
								<h2>People</h2>
								<div class="text-holder">
									<?php if ( ($_SESSION['role']=='O') || ($_SESSION['role']=='A') ) {
										echo '
									<a href="/settings/people/">Add new users and update existing users</a>
											';
									} else {
										echo '
									<a href="/settings/people/">See other users already in the system</a>
											';
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
<!-- / 1st Row of Boxes -->

<?php
if ($_SESSION['role']=='O') 
{
	echo '
<!-- 2nd Row of Boxes -->

		<div class="section-boxes">
			<div class="boxes-holder">
				<div class="boxes-frame">
					<!-- Master Account Box -->
					<div class="box">
						<div class="holder">
							<div class="frame">
								<h2>Master Account</h2>
								<div class="text-holder">
									<a href="/settings/masteraccount/">Update details for ' . $_SESSION['master_account_name'] . ' (billing, private labeling, etc)</a>
								</div>
							</div>
						</div>
					</div>
	';
	echo '
					<!-- Sub Accounts Box -->
					<div class="box">
						<div class="holder">
							<div class="frame">
								<h2>Sub Accounts</h2>
								<div class="text-holder">
									<a href="/settings/subaccount/add/">Add a New Sub Account</a>
								</div>
	';
	
		if (isset($_SESSION['multiaccounts']))
		{
			if ($_SESSION['multiaccounts'])
			{
				echo '
								<select name="editAccountID" id="select-2" onChange="javascript:form.submit();">
									<option value="0">Edit Existing Sub Account:</option>';

				//Loop through accounts to build pulldown menu:
				foreach ($_SESSION['accounts'] as $key => $accountname)
				{
					if ($key!=$_SESSION['master_account'])
					{
						echo '<option value="' . $key . '"';
						echo '>' . $accountname . '</option>';
					}
				}
			    echo '</select>';
			}
		}

		echo '
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
<!-- / 2nd Row of Boxes -->
		';
	}
	?>

</div>
</fieldset>
</form>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
