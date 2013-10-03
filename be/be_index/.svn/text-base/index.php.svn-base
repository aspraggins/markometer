<?php #be_index.php
//Include the configuration file:
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//Set the page title and include the HTML header:
$page_title = 'Administrative Functions';
include('/home/rneel/web/htdocs/includes/header.html');
?>

<!-- Javascript to create confirmation popup window: -->
<script type='text/javascript'>
	function confirmchoice(el) {
		if ( confirm( 'Are you sure?' ) ) {
			document.getElementById(el).value = 1;
			return ( true );
		} else {
			return ( false );
		}
	}
	
</script>

<?php
//Process request for account function:
if (isset($_POST['submittedaccount']))
{
	if ($_POST['confirmation'])
	{
		//Store POSTED variables:
		$acctaction = $_POST['accountaction']; $account = $_POST['account'];
		
		//Code to confirm that choices were actually made for action and account:
		if ($acctaction!='Choose Action' && $account!='Choose Account') 
		{
			require_once (MYSQL);
			
			//Determine action:
			//Activate, Suspend, or Delete an Account:
			if ($acctaction!='NukeAccount')
			{
				//Set appropriate variable for action requested:
				if ($acctaction=='ActivateAccount') 
				{
					$setvar = 'Y'; $echovar = 'Activated';
				}
				if ($acctaction=='SuspendAccount')
				{
					$setvar = 'S'; $echovar = 'Suspended';
				}
				if ($acctaction=='DeleteAccount')
				{
					$setvar = 'D';
					$echovar = 'Deleted. (Data is still available, but not accessible to user)';
				}
				if ($acctaction=='1stWarning')
				{
					$setvar = '1'; $echovar = 'Set to 1st Warning.';
				}
				if ($acctaction=='2ndWarning')
				{
					$setvar = '2'; $echovar = 'Set to 2nd Warning.';
				}

				//Query to Update Account Number specified:
			    $q = "UPDATE accounts SET active='$setvar' WHERE id='$account' LIMIT 1";
			  
			    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			    Error: " . mysqli_error($dbc));
				
				if (mysqli_affected_rows ($dbc) == 1) 
				{
					echo 'Account Number ' . $account . ' ' . $echovar . '<br />';
				}
				else
				{
					echo 'No changes made to database!';
				}
			}
			else
			{
				//Nuke Account:
				//Check Variables for status of nuking account table entry and account_lookup table entry:
				$acctnukesuccess = $acctlookupnukesuccess = FALSE;
				
				//Query to Delete Account Number specified:
			    $q = "DELETE FROM accounts WHERE id='$account' LIMIT 1";
			  
			    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			    Error: " . mysqli_error($dbc));
				
				if (mysqli_affected_rows ($dbc) == 1) $acctnukesuccess = TRUE;
				
				//Query to delete relevant account_lookup table entries:
			    $q = "DELETE FROM account_lookup WHERE account_id='$account'";
			  
			    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			    Error: " . mysqli_error($dbc));
				
				if (mysqli_affected_rows ($dbc) > 0) $acctlookupnukesuccess = TRUE;
				
				if ($acctnukesuccess) 
				{
					echo 'Account Number ' . $account . ' permanently deleted.<br />';
				}
				else
				{
					echo 'No account entries removed from database.';
				}
				if ($acctlookupnukesuccess) 
				{
					echo mysqli_affected_rows ($dbc) . ' Account lookup entries associated with account number ' . $account . ' permanently deleted.<br />';
				}
				else
				{
					echo 'No accounts removed from account lookup database.';
				}
			}
		}
		else
		{
			echo 'Please select both an action and an account!'; 
		}
	}
}

//Process request for user function:
if (isset($_POST['submitteduser']))
{
	if ($_POST['confirmuser'])
	{
		//Store POSTED variables:
		$useraction = $_POST['useraction']; $user = $_POST['user'];
		
		//Code to confirm that choices were actually made for action and account:
		if ($useraction!='Choose Action' && $user!='Choose User') 
		{
			require_once (MYSQL);
			
			//Determine action:
			if ($useraction=='DeleteUser')
			{
				//Query to Update Account Number specified:
			    $q = "UPDATE users SET active='D' WHERE id='$user' LIMIT 1";
			  
			    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			    Error: " . mysqli_error($dbc));
				
				if (mysqli_affected_rows ($dbc) == 1) 
				{
					echo 'User Number ' . $user . ' Deleted.<br />';
				}
				else
				{
					echo 'No changes made to database!';
				}
			}
			if ($useraction=='NukeUser')
			{
				//Check Variables for status of nuking account table entry and account_lookup table entry:
				$usernukesuccess = $acctlookupnukesuccess = FALSE;

				//Query to Delete User Number specified:
			    $q = "DELETE FROM users WHERE id='$user' LIMIT 1";
			  
			    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			    Error: " . mysqli_error($dbc));

				if (mysqli_affected_rows ($dbc) == 1) $usernukesuccess = TRUE;

				//Query to delete relevant account_lookup table entries:
			    $q = "DELETE FROM account_lookup WHERE user_id='$user'";
			  
			    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			    Error: " . mysqli_error($dbc));
				
				if (mysqli_affected_rows ($dbc) > 0) $acctlookupnukesuccess = TRUE;
				
				if ($usernukesuccess) 
				{
					echo 'User Number ' . $user . ' permanently deleted.<br />';
				}
				else
				{
					echo 'No users removed from database.';
				}
				if ($acctlookupnukesuccess) 
				{
					echo mysqli_affected_rows ($dbc) . ' Account lookup entries associated with user number ' . $user . ' permanently deleted.<br />';
				}
				else
				{
					echo 'No users removed from account lookup database.';
				}
			}
		}
		else
		{
			echo 'Please select both an action and an user!'; 
		}
	}
}

//Change Account Owner Stuff..looks up relevant users when a master account is selected from pulldown:
//Check for admissable parameter passed when operator chooses account to change account owner:				
if ( (isset($_REQUEST['AccountOwner'])) && (is_numeric($_REQUEST['AccountOwner'])) )
{
	require_once (MYSQL);

	//Query for admin for selected master account:
	$q = "SELECT user_id  
	FROM account_lookup 
	WHERE account_id={$_REQUEST['AccountOwner']} 
	AND role='O' LIMIT 1";
	
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error($dbc));
	
	//Pull out admin number:
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$accountowner = $row['user_id'];
	
	//Query for all users created by this account owner:
	$q = "SELECT id, first_name, last_name 
	FROM users 
	WHERE (created_by=$accountowner AND active='Y') 
	OR id=$accountowner 
	ORDER BY last_name";

	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error($dbc));
	
	$changeadminusers = array();
	//Build array with users from chosen master account:
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
	{
		$changeadminusers[$row['id']] = $row['first_name'] . " " . $row['last_name'];
	}
	
	//Pull out account name to make default in change owner account pulldown menu:
	$q = "SELECT company 
	FROM accounts 
	WHERE id={$_REQUEST['AccountOwner']} LIMIT 1";
	
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error($dbc));

	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$accountname = $row['company'];
}	
				
//Welcome the user:
if(isset($_SESSION['heavyweight']) && isset($_SESSION['username']))
{
	if ($_SESSION['username']=='AdminX')
	{
		//MySQL:
		require_once (MYSQL);
	
		echo '<h1>Admin</h1>
		<a href="/be/be_logout/">Logout</a>
		<fieldset>
		<p>User and Account Administrative Functions</p>';
		
		//Begin Form:
		echo '<form action="/be/be_index/" method="post">';
		
		//Build Account Actions Pulldown menu:
		echo '<p><b>Accounts:</b><select id="accountaction" name="accountaction">		
		<option>Choose Action</option>
		<option value="ActivateAccount">Activate Account (Turn back on)</option>
		<option value="SuspendAccount">Suspend Account (ie. NonPayment)</option>
		<option value="DeleteAccount">Delete Account (ie. Cancelled Service)</option>
		<option value="NukeAccount">Nuke Account (Delete Records...testing)</option>
		<option value="1stWarning">Set Account to 1st Warning (Delinq. Payment)</option>
		<option value="2ndWarning">Set Account to 2nd Warning (Delinq. Payment)</option>
		</select>';
		
		//Begin Account List Pulldown:
		echo '<select id="account" name="account">		
		<option>Choose Account</option>';
		
		//Query for List of Accounts:
	    $q = "SELECT id, company, active 
	    FROM accounts
	    ORDER BY company";
	  
	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
		
		//Store Number of Accounts for reporting on site statistics fieldset:
		$numaccounts = @mysqli_num_rows($r);
		
		//Build Pulldown for Accounts:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
		{
			$activestring='';
			if ($row['active']!='Y')
			{
				if ($row['active']=='S') $activestring='SUSPENDED';
				if ($row['active']=='D') $activestring='DELETED';
			}
			echo '<option value="' . $row['id'] . '">' . $row['company'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . 
			$activestring . '</option>';
		}
		echo '</select>';
		echo "<input type='hidden' value='0' id='confirmation' name='confirmation' />";
		echo "<input type='submit' value='GO' name='submitaccount' onclick=\"confirmchoice('confirmation');\" />";
		echo '<input type="hidden" name="submittedaccount" value="TRUE" />';		
		echo '</form>';
				
		//Begin Form:
		echo '<form action="/be/be_index/" method="post">';
		
		//Build Users Actions Pulldown menu:
		echo '<p><b>Users:</b><select id="useraction" name="useraction">		
		<option>Choose Action</option>
		<option value="DeleteUser">Delete User (Data Stays)</option>
		<option value="NukeUser">Nuke User (Delete Record...testing)</option></select>';
		
		//Begin User List Pulldown:
		echo '<select id="user" name="user">		
		<option>Choose User</option>';
		
		//Query for List of Users:
	    $q = "SELECT id, email, first_name, last_name, active 
	    FROM users 
	    ORDER BY last_name";
	  
	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
		
		//Store number of users for site statistics:
		$numusers = @mysqli_num_rows($r);		
		
		//Build Pulldown for users:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
		{
			$activestring='';
			if ($row['active']=='D') $activestring='DELETED';
			echo '<option value="' . $row['id'] . '">' . $row['last_name'] . ',' . 
			$row['first_name'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . $row['email'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . 
			$activestring . '</option>';
		}
		echo '</select>';
		echo "<input type='hidden' value='0' id='confirmuser' name='confirmuser' />";
		echo "<input type='submit' value='GO' name='submituser' onclick=\"confirmchoice('confirmuser');\" />";
		echo '<input type="hidden" name="submitteduser" value="TRUE" />';		
		echo '</form>';
//###################################################################		
		//Change Account Owner Functions:
		//Begin Account List Pulldown:
		echo '<form action="/be/be_index/" method="post">
		<hr><p>Change Account Owner</p><p><b>Select Account:
		</b><select id="AccountOwner" name="AccountOwner" 
		onChange="javascript:form.submit();">';
		
		if (isset($accountname)) 
		{
			echo '<option>' . $accountname . '</option>';
		}
		else
		{
			echo '<option>Choose Account</option>';
		}
		
		//Query for List of Accounts:
	    $q = "SELECT id, company, active 
	    FROM accounts 
		WHERE agency='Y' 
	    ORDER BY company";
	  
	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
		
		//Build Pulldown for Accounts:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
		{
			$activestring='';
			if ($row['active']!='Y')
			{
				if ($row['active']=='S') $activestring='SUSPENDED';
				if ($row['active']=='D') $activestring='DELETED';
			}
			echo '<option value="' . $row['id'] . '">' . $row['company'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . 
			$activestring . '</option>';
		}
		echo '</select></form>';

		echo '<form action="/be/be_index/change_admin/" method="post">';
		//Build dynamic user pulldown menu:
		echo '<select id="AdminUsers" name="AdminUsers">		
		<option>Choose User</option>';
		
		if (isset($changeadminusers))
		{
			foreach ($changeadminusers as $key => $cau)
			{
				echo '<option value="' . $key . '">' . $cau;
				if ($key==$accountowner) echo '&nbsp;&nbsp;&nbsp;&nbsp;(Current Admin)';
				echo '</option>';
			}
		}
		echo '</select>';
		
		echo "<input type='hidden' value='";
		if (isset($accountowner)) echo $accountowner;
		echo "' id='OldAdmin' name='OldAdmin' />";		
		echo "<input type='submit' value='GO' name='submitadmin'>";
		echo '</form>';
		
//#######################################################################		
		echo '</fieldset>';

		echo '<fieldset>
		<p>Site Statistics</p>
		<p><b>Number of Accounts:&nbsp;&nbsp;' . $numaccounts . '</b></p>';
		
		echo '<p><b>Number of Users:&nbsp;&nbsp;' . $numusers . '</b></p>';
				
		echo '</fieldset>';
		
		//Build Messaging interface:
		echo '<fieldset>
		<p>Messaging</p>
		<p><b>Active Messages:</b></p>';
		//SQL code here to select messages that are active, show table with:
		//message number, message text, admin only checkbox, dismiss checkbox, save button
		
		//Query for currently active messages:
	    $q = "SELECT id, message, adminonly 
		FROM messages 
		WHERE active='Y' 
	    ORDER BY id";

	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
		
		//Build table:
		echo '<table cellspacing="3" cellpadding="5" width="75%" border="1">
		<tr><td align="left">ID</td>
		<td align="left">Message</td>
		<td align="left">Account Owner Only?</td>
		<td align="left">Edit Message</td></tr>';
		
		
		//Build List of messages and options:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
		{
			//display message number:
			echo '<tr><td align="left">' . $row['id'] . '</td>';
			//display message text:
			echo '<td align="left">' . $row['message'] . '</td>';
			//admin only checkbox:
			echo '<td align="center">';
			if ($row['adminonly']=="Y") {echo 'Y';}	else {echo 'N';}
			echo '</td>';
			
			//Edit Message Button:
			$buttonstring = "'edit_message/?id=" . $row['id'] . "'";
			echo '<td align="center">
			<input type="button" value="Edit Message" onclick="window.location.href=' . 
			$buttonstring . '"></td>';		
			
			echo '</tr>';
		}
		echo '</table>';
		echo '<br /><p><a href="/be/be_index/add_message/">Add New Message</a></p>';
		echo '</fieldset>';
	}

}
else
{
	$url = '/be/';
	header("Location: $url");
	exit();
}

?>