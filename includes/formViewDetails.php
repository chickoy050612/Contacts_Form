<!-- 
1. purpose: This file is to implement a web page to display the details about a contact. 
The page is to be displayed when the ‘View Details button is clicked on the contact list page
provided that a contact was selected on the list.
2. authors: group 6
-->
<?php
function formViewDetails($db_conn){
    $ct_id=$_POST['list_select'];
	$qry = "select * from contact 
			left join contact_address on ct_id = ad_ct_id 
			left join contact_phone on ct_id = ph_ct_id
			left join contact_email on ct_id = em_ct_id
			left join contact_web on ct_id = we_ct_id
			left join contact_note on ct_id = no_ct_id    
			WHERE ct_id=?";
    $stmt = $db_conn->prepare($qry);
	if (!$stmt){
		echo "<p>Error in view prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
		exit(1);
	}
	
	$status = $stmt->execute($ct_id);
	if ($status){
		if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
	<h3>View Details </h3>
	<form method="POST">
	<table  border="1">
	<tr><td>Contact Type</d><td><?php echo $row['ct_type']; ?></td></tr>
	<tr><td>Display/Business Name</d><td><?php echo $row['ct_disp_name']; ?></td></tr>
	<tr><td>First Name</td><td><?php echo $row['ct_first_name']; ?></td></tr>
	<tr><td>Last Name</td><td><?php echo $row['ct_last_name']; ?></td></tr>
<?php if (isset($row['ad_type'])){ ?>
	<tr><td><br></td><td></td></tr>
	<tr><td>Address Type</td><td><?php echo $row['ad_type']; ?></td></tr>
<?php } ?>
<?php if (isset($row['ad_line_1'])){ ?>
	<tr><td>Address Line 1</td><td><?php echo $row['ad_line_1']; ?></td></tr>
<?php } ?>
<?php if (isset($row['ad_line_2'])){ ?>
	<tr><td>Address Line 2</td><td><?php echo $row['ad_line_2']; ?></td></tr>
<?php } ?>
<?php if (isset($row['ad_line_3'])){ ?>
	<tr><td>Address Line 3</td><td><?php echo $row['ad_line_3']; ?></td></tr>
<?php } ?>
<?php if (isset($row['ad_city'])){ ?>
	<tr><td>City</td><td><?php echo $row['ad_city']; ?></td></tr>
<?php } ?>
<?php if (isset($row['ad_province'])){ ?>
	<tr><td>Province</td><td><?php echo $row['ad_province']; ?></td></tr>
<?php } ?>
<?php if (isset($row['ad_post_code'])){ ?>
	<tr><td>Post Code</td><td><?php echo $row['ad_post_code']; ?></td></tr>
<?php } ?>
<?php if (isset($row['ad_country'])){ ?>
	<tr><td>Country</td><td><?php echo $row['ad_country']; ?></td></tr>
<?php } ?>
<?php if (isset($row['ph_type'])){ ?>
	<tr><td><br></td><td></td></tr>
	<tr><td>Phone Type</td><td><?php echo $row['ph_type']; ?></td></tr>
<?php } ?>
<?php if (isset($row['ph_number'])){ ?>
	<tr><td>Phone Number</td><td><?php echo $row['ph_number']; ?></td></tr>
<?php } ?>
<?php if (isset($row['em_type'])){ ?>
	<tr><td><br></td><td></td></tr>
	<tr><td>Email Type</td><td><?php echo $row['em_type']; ?></td></tr>
<?php } ?>
<?php if (isset($row['em_email'])){ ?>
	<tr><td>Email Address</td><td><?php echo $row['em_email']; ?></td></tr>
<?php } ?>
<?php if (isset($row['we_type'])){ ?>
	<tr><td><br></td><td></td></tr>
	<tr><td>Web Site Type</td><td><?php echo $row['we_type']; ?></td></tr>
<?php } ?>
<?php if (isset($row['we_url'])){ ?>
	<tr><td>Web Site URL</td><td><?php echo $row['we_url']; ?></td></tr>
<?php } ?>
<?php if (isset($row['no_note'])){ ?>
	<tr><td><br></td><td></td></tr>
	<tr><td>Note</td><td><?php echo $row['no_note']; ?></td></tr>
<?php } ?>
	</table>
    <table>
   
    <tr>
		<td><input type="submit" name="ct_b_list" value="Retern to List"></td>
    </tr>
    </table>
    </form>
<?php
    } else {
        echo "<div>\n";
        echo "<p>No contacts to display</p>\n";
        echo "</div>\n";
    }
    } else {
    echo "<p>Error in view execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
    exit(1);
    }
}

function validateViewDetails(){
	$err_msgs = array();
	if (!isset($_POST['list_select'])){
		$err_msgs[] = "One of the lists must be selected";
	} 
	return $err_msgs;
}
?>

