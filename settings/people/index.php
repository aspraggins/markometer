<?php # Settings - People Page
$metatitle = 'People';
$returnurl = '/login/?returnpage=settings/people';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>People</h1>
<?php
if ( ($_SESSION['role']=='O') || ($_SESSION['role']=='A') )
{
# Help Button
$helphref = 'settings-people-manage.html';
$helptitle = 'Help with Managing People';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
}
else
{
# Help Button
$helphref = 'settings-people.html';
$helptitle = 'Help with People';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
}
?>
</div>

<!-- Main Content -->

<?php 

//MYSQL Setup:
require_once(MYSQL);

if (isset($_REQUEST['action']))
{
	if ($_REQUEST['action']=='useradded')
	{
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>User successfully added.</p>
			</div>
		</div>
	</div>
</div>';
	}
}

?>

<div class="main-block main-block-add">

<form class="main-form">
<fieldset>
	
<?php

// Show Add User Button for Admins and Account Owners

if ( ($_SESSION['role']=='O') || ($_SESSION['role']=='A') )
{
echo '
<div class="content-link top-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="/settings/people/add/"><span>Add a New User &gt;</span></a>
		</div>
	</div>
</div>
<br/>
';
}

?>

<?php 
if($_SESSION['multiaccounts']) {
if (isset($_SESSION['accountname']))
echo '
<div class="heading">
	<h2>Active Account: ' . $_SESSION['accountname'] . '</h2>
</div>
'; 
}
?>

<!-- Active Account Users Table -->

<div class="main-box">
	<div class="main-table">
		<table class="sortable">
			<thead>
				<tr>
					<th class="long sortcol"><span>User</span></th>
					<th class="sortcol"><span>Company</span></th>
					<th class="sortcol"><span>Email</span></th>
					<th class="short sortcol"><span>Role</span></th>
				</tr>
			</thead>
			<tbody>
<?php

// code here to build list of people associated with:
// for admin: all people he is associated with on any project
// for subuser: people associated with this account

//Show admin first:
if ($_SESSION['role']=='O')
{
	// if this person is the admin:
	echo '				<tr>
					<td class="long"><a href="/settings/myinfo/">' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '</a></td>
					<td>' . $_SESSION['company'] . '</td>
					<td><a href="mailto:' . $_SESSION['email'] . '">' . $_SESSION['email'] . '</a></td>
					<td class="short">Account Owner</td>
				</tr>';
	
}
else
{
	$q = "SELECT DISTINCT account_lookup.user_id, 
	users.first_name, users.last_name, users.email, users.date_created, 
	users.company, users.title 
	FROM account_lookup 
	INNER JOIN users 
	ON users.id=account_lookup.user_id 
	WHERE account_lookup.role='O' AND users.active!='D' 
	AND account_lookup.account_id={$_SESSION['account_id']}";

	$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
	mysqli_error($dbc));

	if (mysqli_num_rows($r)==1)
	{
		$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	echo '
				<tr>
					<td class="long">' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
					<td>' . $row['company'] . '</td>
					<td><a href="mailto:' . $row['email'] . '">' . $row['email'] . '</a></td>
					<td class="short">Account Owner</td>
				</tr>';
	}	
}

// Build wherestring for extracting users for current account:
$wherestring = " WHERE account_lookup.account_id={$_SESSION['account_id']}";
$wherestring .= ' AND account_lookup.role!="O" AND users.active!="D"';

//Build query:
$q = "SELECT account_lookup.user_id, account_lookup.role, account_lookup.assigns_leads, 
users.first_name, users.last_name, users.email, users.date_created, 
users.company, users.title
FROM account_lookup 
INNER JOIN users
ON users.id=account_lookup.user_id" . 
$wherestring . 
' ORDER BY users.last_name';
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

if (mysqli_num_rows($r) > 0)
{
	//Fetch and display records:
	while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
	{
		echo '
				<tr>
					<td class="long">';
		if ( ($_SESSION['role']=='O') || ($_SESSION['role']=='A') )
		{
			// Create edit link for user:
			$editstring = "/settings/people/edit/?id=" . $row['user_id'] . "";	
			echo '<a href="' . $editstring . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</a>';		
		}
		else
		{
			// Just show user name:
			echo '' . $row['first_name'] . ' ' . $row['last_name'] . '';		
		}		
					
		echo '</td>
					<td>' . $row['company'] . '</td>
					<td><a href="mailto:' . $row['email'] . '">' . $row['email'] . '</a></td>
					<td class="short">';
		switch ($row['role'])
		{
			case 'A':
				$role = 'Administrator'; break;
			case 'M':
				$role = 'Marketing'; break;
			case 'S':
				$role = 'Sales'; break;
			case 'V':
				$role = 'Executive'; break;
		}
		if ($row['assigns_leads']=='Y') {
			$role .= ' <br/>(Can Assign Leads)';
		} else {
			$role .= '';
		}
		echo  $role;	
					
		echo '</td>
				</tr>'; 		

	}
}
else
{
	echo '
				<tr><td colspan="4" style="text-align:center;">
				There are currently no other users associated with this account
				</td></tr>';
}

?>

			</tbody>
		</table>
	</div>
</div>


<?php

// Code to find all other users associated with this account owner, what accounts they may be associated with:
// Array for accounts to check:
$checkaccounts = array();

//Put account numbers in $checkaccounts based on admin being single or multi accounts:
if ($_SESSION['multiaccounts']) {
	foreach ($_SESSION['accounts'] as $key => $accountname) {
		//check to see if this user is an admin for the account in question:
		$q = "SELECT role FROM account_lookup " . 
		"WHERE user_id={$_SESSION['id']} AND account_id='$key'";

		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));
		
		$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
		
		if($key!=$_SESSION['account_id']) {
			if($row['role']=='A' OR $row['role']=='O') {
				$checkaccounts[] = $key;
			}
		}
	}
} else {
	if($_SESSION['role']=='A' OR $_SESSION['role']=='O') {
		$checkaccounts[] = $_SESSION['account_id'];
	}
}

// Build $wherestring for admin:
if ( ($_SESSION['role']=='O') || ($_SESSION['role']=='A') )
{
	$wherestring = ' WHERE account_lookup.account_id IN (';
	$orphanstring = ' WHERE default_account IN (';
	foreach ($checkaccounts as $accountno)
	{
		if ($accountno!=$_SESSION['account_id']) $wherestring .= $accountno . ',';
		$orphanstring .= $accountno . ',';
	}
	$wherestring = substr($wherestring, 0, strlen($wherestring) - 1) . ')';
	$orphanstring = substr($orphanstring, 0, strlen($orphanstring) - 1) . ')';

	// add to wherestring to exclude this account:
	$wherestring .= " AND account_lookup.user_id NOT IN (SELECT user_id FROM account_lookup WHERE account_id={$_SESSION['account_id']}) AND users.active!='D'";
	// add to orphan string to include subquery that strips it down to users with no account_lookup entries:
	$orphanstring .= " AND id NOT IN (SELECT user_id FROM account_lookup) AND users.active!='D'";

	if(count($checkaccounts)>0) {

		// Only query for these other users if multiaccount:
		if ($_SESSION['multiaccounts'])
		{
			// query for users that are not in this account, but are in others..:
			$q = 'SELECT account_lookup.user_id, account_lookup.account_id, account_lookup.role, 
			users.first_name, users.last_name, users.email, users.date_created, users.title, 
			users.company, GROUP_CONCAT(accounts.company SEPARATOR "<br />") as accounts
			FROM account_lookup 
			INNER JOIN users
			ON account_lookup.user_id=users.id 
			INNER JOIN accounts 
			ON account_lookup.account_id=accounts.id' .
			$wherestring . 
			' GROUP BY account_lookup.user_id 
			ORDER BY users.last_name';

			$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
			mysqli_error($dbc));

			if (mysqli_num_rows($r) > 0)
			{
				echo '
	<div class="heading">
		<h2>Other Users in Other Accounts</h2>
	</div>

	<!-- Other Users Table -->

	<div class="main-box">
		<div class="main-table">
			<table class="sortable">
				<thead>
					<tr>
						<th class="long sortcol"><span>User</span></th>
						<th class="sortcol"><span>Company</span></th>
						<th class="sortcol"><span>Email</span></th>
						<th class="short sortcol"><span>Account(s)</span></th>
					</tr>
				</thead>
				<tbody>';

				// Fetch and display records:
				while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
				{
					echo '
					<tr>
						<td class="long">';
					// Create edit link for user:
					$editstring = "/settings/people/edit/?id=" . $row['user_id'] . "";	
					echo '<a href="' . $editstring . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</a>';
					echo '</td>
						<td>' . $row['company'] . '</td>
						<td><a href="mailto:' . $row['email'] . '">' . $row['email'] . '</a></td>
						<td class="short">' . $row['accounts'] . '</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	';		
				}
			}
		}
	}
	
	if ($_SESSION['role']=='O')
	{

		// Build query to find orphans...in no accounts, but created by this owner:
		$q = "SELECT id, email, first_name, last_name, company, title, date_created
	    FROM users " .
	    $orphanstring .
	    " ORDER BY last_name";
	
		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));

		if (mysqli_num_rows($r) > 0)
		{
				echo '
	<div class="heading">
		<h2>Orphaned Users (Created but not in any accounts)</h2>
	</div>

	<!-- Orphaned Users Table -->

	<div class="main-box">
		<div class="main-table">
			<table class="sortable">
				<thead>
					<tr>
						<th class="long sortcol"><span>User</span></th>
						<th class="sortcol"><span>Company</span></th>
						<th class="sortcol"><span>Email</span></th>
						<th class="short sortcol"><span>Account</span></th>
					</tr>
				</thead>
				<tbody>';

			// Fetch and display records:
			while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
			{
				echo '
				<tr>
					<td class="long">';
				// Create edit link for user:
				$editstring = "/settings/people/edit/?id=" . $row['id'] . "";	
				echo '<a href="' . $editstring . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</a>';
				echo '</td>
					<td>' . $row['company'] . '</td>
					<td><a href="mailto:' . $row['email'] . '">' . $row['email'] . '</a></td>
					<td class="short">None</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
';		
			}
		}
	}
}
?>


</fieldset>
</form>

</div>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
