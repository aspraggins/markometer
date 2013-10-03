<?php
//Script to automatically check for lead followups, send email notifications

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
require_once (MYSQL);

//redo for new leads_followup table:
//first come up with list of users who need followup notifies today:

$q = "SELECT DISTINCT lead_followups.user_id, users.email, users.first_name, users.last_name, users.master_account " . 
"FROM lead_followups INNER JOIN users ON lead_followups.user_id=users.id " . 
"WHERE UNIX_TIMESTAMP( due_date ) BETWEEN UNIX_TIMESTAMP( CURDATE( ) ) AND UNIX_TIMESTAMP( DATE_ADD( CURDATE( ) , INTERVAL + 86399 SECOND ) )";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error($dbc));

//cycle through found users, query for lead_id's:
while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
    echo '<b>User:' . $row['user_id'] . '&nbsp;&nbsp;&nbsp;' . 
    $row['email'] . '&nbsp;&nbsp;&nbsp;' . $row['first_name'] . '&nbsp;' . 
    $row['last_name'] . '</b><br />';
    
	//query for master account, see if this email will need to be private labeled:
	$masteraccount = $row['master_account'];
	$q3 = "SELECT company FROM accounts " . 
	"WHERE id='$masteraccount' AND privatelabel='Y'";
	    	
	$r3 = mysqli_query ($dbc, $q3) or trigger_error("Query: 
	$q3\n<br />MySQL Error: " . mysqli_error($dbc));
		    
	$row3 = mysqli_fetch_array($r3, MYSQLI_ASSOC);
		    
	if (mysqli_num_rows($r3) == 1) {
		$from = 'From:' . str_replace(" ","_",$row3['company']) . '@mp41.com';
		$subject = $row3['company'] . ' Lead Followup';
	} else {
		$from = 'From:Markometer@mp41.com';
		$subject = 'Markometer Lead Followup';
	}
	
    $userid = $row['user_id'];
    $q2 = "SELECT lead_followups.lead_id, leads.company, leads.account_id " . 
    "FROM lead_followups INNER JOIN leads ON lead_followups.lead_id=leads.idlead " . 
    "WHERE lead_followups.user_id='$userid' " . 
    "AND UNIX_TIMESTAMP( lead_followups.due_date ) BETWEEN UNIX_TIMESTAMP( CURDATE( ) ) AND UNIX_TIMESTAMP( DATE_ADD( CURDATE( ) , INTERVAL + 86399 SECOND ) )";

    $r2 = mysqli_query ($dbc, $q2) or trigger_error("Query: $q2\n<br />MySQL
	Error: " . mysqli_error($dbc));

    //cycle through leads for this user, query leads, user info and prep string for email:
    $companies = '';
    while ($row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC)) {
	echo 'Leads:' . $row2['lead_id'] . '&nbsp;&nbsp;' . $row2['company'] . '<br />';
	$companies .= "\n" . $row2['company'];
    }
    echo '<br />';
    //Build email:
    //$subject = 'Markometer Lead Followup';
    //$body = 'Hello! This is a friendly reminder that you have followups due today for the following lead(s):';
    //$body .= $companies;

	//open template for this email:
	$openfile = $_SERVER[‘DOCUMENT_ROOT’] . '/templates/emails/followupmail.txt';
	$fp = @fopen($openfile, "rb") or die("Couldn't open file");
	$data = '';
	while(!feof($fp)) {
		$data .= fgets($fp, 1024);	
	}	
	fclose($fp); 

	//Process $data to replace tags ([fname] and [recipient]) with strings:
	$data = str_replace("[fname]", $row['first_name'], $data);
	$body = str_replace("[content]", $companies, $data);


    $sent = mail($row['email'], $subject, $body, $from);
    if($sent) {
	    echo 'Message Successfully Sent!<br /><br />';
    }
}

mysqli_free_result($r);
mysqli_close($dbc);

exit(); //Quit the script
?>