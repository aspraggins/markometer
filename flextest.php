<?php 
//test page for interaction with VIISE site:
//include ($_SERVER['DOCUMENT_ROOT'] . '/VIISEDEV/includes/header.php');
?>
<html><body>
<div style="width:600px">

	<!-- create form for submission of login info: -->
	<form action="https://viise.ghinternational.com/VIISEDEV/login.php" method="GET">	
		<fieldset>
			<h3>VIISE Awareness Account Login</h3>
			<label>Email</label>
		 		<input type="text" name="email" id="email" maxlength="30" />
			&nbsp;&nbsp;
			<label>Password</label>
				<input type="password" name="pass" maxlength="50" />
				<br /><br /><br />
				<input type="submit" value="Log In">		
		</fieldset>
	</form>
	<br /><br />
	<p><b>This form simulates the incoming traffic coming from the VIISE front end.</b></p><br /> 
	<p>The VIISE front end login will issue a GET to:</p>
	<p><i>"/VIISEDEV/login.php"</i></p> 
	<p>using two variables, email and pass, over a secure connection.</p>
	<p>Passwords is an md5 encryption hash.</p><hr />
	<table border="0"><tr><td><b>Current users in the MySQL table:</b></td></tr>
	<tr><td>hrearden@reardensteel.com</td><td>password: dagny</td></tr>
	<tr><td>wmouch@bureau.gov</td><td>password: stuff</td></tr></table>

</div>
</body></html>
<?php
//include ($_SERVER['DOCUMENT_ROOT'] . '/VIISEDEV/includes/footer.php'); 
?>