<div id="content" style="width: 82%; position: absolute; padding: 20px; background-color: #d1fafe;">

<?php
	echo "<h2> Master User </h2>" . "<br/>";
		
	echo form_open_multipart('welcome/masterUser');
	?>
		Username: <input type="text" id="username" name="username" size="32" placeholder="meloniaseven" required="required"/><br/>
		Password: <input type="password" id="password" name="password" size="32" placeholder="12345678"/><br/>
		E-mail: <input type="email" id="email" name="email" placeholder="meloniaseven@gmail.com"/><br/>
		Full name: <input type="text" id="name" name="name" placeholder="Melania Laniwati"/><br/>
		Birthdate: <input type="date" id="birthdate" name="birthdate" placeholder="08-17-1945"/><br/>
	<?php
		echo "Profile picture: " . form_upload('profilePicture') . "<br/><br/>";
		
		echo form_submit('insert', 'Insert') . "&nbsp &nbsp &nbsp";
		echo form_submit('update', 'Update');
	echo form_close();
	
	echo "<br/> <b>Status:</b> " . $conf . "<br/><br/><br/>";
	
	//print_r($users);
	echo "<table cellspacing='10px' cellpadding='10px' border='1' align='center'>
			<tr>
				<td><b>Username</b></td>
				<td><b>Password</b></td>
				<td><b>Email</b></td>
				<td><b>Full Name</b></td>
				<td><b>Birth date</b></td>
				<td><b>Profile picture</b></td>
			</tr>";
    for($i=0; $i<sizeof($users); $i++) {
    	echo "<tr>";
        echo "<td>" . $users[$i]['username'] . "</td>";
        echo "<td>" . $users[$i]['password'] . "</td>";
        echo "<td>" . $users[$i]['email'] . "</td>";
        echo "<td>" . $users[$i]['name'] . "</td>";
        echo "<td>" . date("Y-m-d", strtotime($users[$i]['birthdate'])) . "</td>";
        echo "<td><img style='width:100px;height:100px;'src='" . $b_url.$users[$i]['picture'] . "'/></td>";
        echo "</tr>";
    }
    echo "</table>";
?>

</div>