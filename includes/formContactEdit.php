<!-- 
1. purpose: This file is to implement the functionality to allow a user to edit a contact’s information. 
This file use multiple forms (similar to the add functionality). 
The form is to be displayed when a user selects a contact from the contacts list and clicks the ‘Edit’ button. 
When the user is finished editing, the user is able to save any changes made or discard the changes.
2. authors: group 6
-->
<?php
function formContactEdit(){
?>
	<h3>Update Contact </h3>
	<form name="addForm" method="POST" >
	<table border="1">
	<tr><td>Contact Type</d><td><?php echo $_SESSION['ct_type']; ?></td></tr>
	<tr><td>Display/Business Name</d><td><?php echo $_SESSION['ct_disp_name']; ?></td></tr>
	<tr><td>First Name</td><td><?php echo $_SESSION['ct_first_name']; ?></td></tr>
	<tr><td>Last Name</td><td><?php echo $_SESSION['ct_last_name']; ?></td></tr>
<?php if (isset($_SESSION['ad_type'])){ ?>
	<tr><td><br></td><td></td></tr>
	<tr><td>Address Type</td><td><?php echo $_SESSION['ad_type']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['ad_line_1'])){ ?>
	<tr><td>Address Line 1</td><td><?php echo $_SESSION['ad_line_1']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['ad_line_2'])){ ?>
	<tr><td>Address Line 2</td><td><?php echo $_SESSION['ad_line_2']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['ad_line_3'])){ ?>
	<tr><td>Address Line 3</td><td><?php echo $_SESSION['ad_line_3']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['ad_city'])){ ?>
	<tr><td>City</td><td><?php echo $_SESSION['ad_city']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['ad_province'])){ ?>
	<tr><td>Province</td><td><?php echo $_SESSION['ad_province']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['ad_post_code'])){ ?>
	<tr><td>Post Code</td><td><?php echo $_SESSION['ad_post_code']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['ad_country'])){ ?>
	<tr><td>Country</td><td><?php echo $_SESSION['ad_country']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['ph_type'])){ ?>
	<tr><td><br></td><td></td></tr>
	<tr><td>Phone Type</td><td><?php echo $_SESSION['ph_type']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['ph_number'])){ ?>
	<tr><td>Phone Number</td><td><?php echo $_SESSION['ph_number']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['em_type'])){ ?>
	<tr><td><br></td><td></td></tr>
	<tr><td>Email Type</td><td><?php echo $_SESSION['em_type']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['em_email'])){ ?>
	<tr><td>Email Address</td><td><?php echo $_SESSION['em_email']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['we_type'])){ ?>
	<tr><td><br></td><td></td></tr>
	<tr><td>Web Site Type</td><td><?php echo $_SESSION['we_type']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['we_url'])){ ?>
	<tr><td>Web Site URL</td><td><?php echo $_SESSION['we_url']; ?></td></tr>
<?php } ?>
<?php if (isset($_SESSION['no_note'])){ ?>
	<tr><td><br></td><td></td></tr>
	<tr><td>Note</td><td><?php echo $_SESSION['no_note']; ?></td></tr>
<?php } ?>
	</table>
    <table>
    <tr>
        <td><input type="submit" name="ct_b_back" value="Back"></td>
        <td><input type="submit" name="ct_b_next" value="Save"></td>
    </tr>
    <tr>
		<td><input type="submit" name="ct_b_cancel" value="Cancel"></td>
    </tr>
    </table>
	</form>
<?php
}
?>
<?php
function updateContact($db_conn){
    $ct_id=$_SESSION['list_select'][0];
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

    $status = $stmt->execute($_SESSION['list_select']);
    if ($status){
        if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $field_data = array();
            if (isset($_SESSION['ad_type'])||isset($row['ad_type'])){
                if(isset($row['ad_type'])){
                    $state="update";
                    $id=" where ad_ct_id=$ct_id";
                }
                else{
                    $state="insert into ";
                    $id=", ad_ct_id=$ct_id";
                }
                $qry_ad = "$state contact_address set ad_type= ?";
                $field_data[] = $_SESSION['ad_type'];
                if (isset($_SESSION['ad_line_1'])){
                    $qry_ad .= ", ad_line_1= ?";
                    $field_data[] = $_SESSION['ad_line_1'];
                }
                if (isset($_SESSION['ad_line_2'])){
                    $qry_ad .= ", ad_line_2= ?";
                    $field_data[] = $_SESSION['ad_line_2'];
                }
                if (isset($_SESSION['ad_line_3'])){
                    $qry_ad .= ", ad_line_3= ?";
                    $field_data[] = $_SESSION['ad_line_3'];
                }
                if (isset($_SESSION['ad_city'])){
                    $qry_ad .= ", ad_city= ?";
                    $field_data[] = $_SESSION['ad_city'];
                }
                if (isset($_SESSION['ad_province'])){
                    $qry_ad .= ", ad_province= ?";
                    $field_data[] = $_SESSION['ad_province'];
                }
                if (isset($_SESSION['ad_post_code'])){
                    $qry_ad .= ", ad_post_code= ?";
                    $field_data[] = $_SESSION['ad_post_code'];
                }
                if (isset($_SESSION['ad_contry'])){
                    $qry_ad .= ", ad_country= ?";
                    $field_data[] = $_SESSION['ad_country'];
                }
                
                $qry_ad .= ", ad_active= ?";
                $field_data[] = "y";
                $qry_ad .= " $id;";
    
                $stmt = $db_conn->prepare($qry_ad);
                if (!$stmt){
                    echo "<p>Error in address prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
                    exit(1);
                }
                $status = $stmt->execute($field_data);
                if (!$status){
                    echo "<p>Error in address execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
                    exit(1);
                }
            }
            unset($field_data);

            $field_data = array();
            if (isset($_SESSION['ph_type'])||isset($row['ph_type'])){
                if(isset($row['ph_type'])){
                    $state="update";
                    $id="where ph_ct_id=$ct_id";
                }
                else{
                    $state="insert into ";
                    $id=", ph_ct_id=$ct_id";
                }
                $qry_ph = "$state contact_phone set ph_type = ?";
                $field_data[] = $_SESSION['ph_type'];
                if (isset($_SESSION['ph_number'])){
                    $qry_ph .= ", ph_number= ?";
                    $field_data[] = $_SESSION['ph_number'];
                }
                $qry_ph .= ", ph_active= ? ";
                $qry_ph .= " $id;";
                $field_data[] = "Y";

                $stmt = $db_conn->prepare($qry_ph);
                if (!$stmt){
                    echo "<p>Error in phones prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
                    exit(1);
                }
                $status = $stmt->execute($field_data);
                if (!$status){
                    echo "<p>Error in phone execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
                    exit(1);
                }
            }
            unset($field_data);

            $field_data = array();
            if (isset($_SESSION['em_type'])||isset($row['em_type'])){
                if(isset($row['em_type'])){
                    $state="update";
                    $id="where em_ct_id=$ct_id";
                }
                else{
                    $state="insert into ";
                    $id=", em_ct_id=$ct_id";
                }
                $qry_em = "$state contact_email set em_type  = ?";
                $field_data[] = $_SESSION['em_type'];
                if (isset($_SESSION['em_email'])){
                    $qry_em .= ", em_email= ?";
                    $field_data[] = $_SESSION['em_email'];
                }
                $qry_em .= ", em_active= ? ";
                $qry_em .="$id ;";
                $field_data[] = "Y";

                $stmt = $db_conn->prepare($qry_em);
                if (!$stmt){
                    echo "<p>Error in email prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
                    exit(1);
                }
                $status = $stmt->execute($field_data);
                if (!$status){
                    echo "<p>Error in email execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
                    exit(1);
                }
            }
            unset($field_data);

            $field_data = array();
            if (isset($_SESSION['we_type'])||isset($row['we_type'])){
                if(isset($row['we_type'])){
                    $state="update";
                    $id="where we_ct_id=$ct_id";
                }
                else{
                    $state="insert into ";
                    $id=", we_ct_id=$ct_id";
                }
                $qry_we = "$state contact_web set we_type = ?";
                $field_data[] = $_SESSION['we_type'];
                if (isset($_SESSION['we_url'])){
                    $qry_we .= ", we_url= ?";
                    $field_data[] = $_SESSION['we_url'];
                }
                $qry_we .= ", we_active= ?" ;
                $field_data[] = "Y";
                $qry_we .=" $id ;";
 
                $stmt = $db_conn->prepare($qry_we);
                if (!$stmt){
                    echo "<p>Error in URL prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
                    exit(1);
                }
                $status = $stmt->execute($field_data);
                if (!$status){
                    echo "<p>Error in URL execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
                    exit(1);
                }
            }
            unset($field_data);

            $field_data = array();
            if (isset($_SESSION['no_note'])||isset($row['no_note'])){
                if(isset($row['no_note'])){
                    $state="update";
                    $id="where no_ct_id=$ct_id";
                }
                else{
                    $state="insert into ";
                    $id=", no_ct_id=$ct_id";
                }
                $qry_no = "$state contact_note  set no_type= ?";
                $field_data[] = "";
                $qry_no .= ", no_note= ? ";
                $field_data[] = $_SESSION['no_note'];
                $qry_no .=" $id ;";
    
                $stmt = $db_conn->prepare($qry_no);
                if (!$stmt){
                    echo "<p>Error in note prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
                    exit(1);
                }
                $status = $stmt->execute($field_data);
                if (!$status){
                    echo "<p>Error in note execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
                    exit(1);
                }
            }
            unset($field_data);
            
            $field_data = array();
            $qry_ct = "update contact set ct_type= ?";
            $field_data[] = $_SESSION['ct_type'];
            if (isset($_SESSION['ct_first_name'])){
                $qry_ct .= ", ct_first_name= ?";
                $field_data[] = $_SESSION['ct_first_name'];
            }
            if (isset($_SESSION['ct_last_name'])){
                $qry_ct .= ", ct_last_name= ?";
                $field_data[] = $_SESSION['ct_last_name'];
            }
            if (isset($_SESSION['ct_disp_name'])){
                $qry_ct .= ", ct_disp_name= ?";
                $field_data[] = $_SESSION['ct_disp_name'];
            }
            $qry_ct .= ", ct_deleted= ? where ct_id=?";
            $field_data[] = "N";
            $field_data[] = $ct_id;
            $stmt = $db_conn->prepare($qry_ct);
            if (!$stmt){
                echo "<p>Error in contact prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
                exit(1);
            }
            $status = $stmt->execute($field_data);
            if (!$status){
                echo "<p>Error in contact execute: ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
                exit(1);
            }
            unset($field_data);
        }
        else {
            echo "<div>\n";
            echo "<p>No contacts to display</p>\n";
            echo "</div>\n";
        }
    } else {
    echo "<p>Error in view execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
    exit(1);
    }
}

function editData($db_conn){
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
            $_SESSION['ct_type']=$row['ct_type'];
            $_SESSION['ct_disp_name']=$row['ct_disp_name'];
            $_SESSION['ct_first_name']=$row['ct_first_name'];
            $_SESSION['ct_last_name']=$row['ct_last_name'];
            if (isset($row['ad_type'])){
                $_SESSION['ad_type']=$row['ad_type'];
            }
            if (isset($row['ad_line_1'])){
                $_SESSION['ad_line_1']=$row['ad_line_1'];
            }
            if (isset($row['ad_line_2'])){
                $_SESSION['ad_line_2']=$row['ad_line_2'];
            }
            if (isset($row['ad_line_3'])){
                $_SESSION['ad_line_3']=$row['ad_line_3'];
            }
            if (isset($row['ad_city'])){
                $_SESSION['ad_city']=$row['ad_city'];
            }
            if (isset($row['ad_province'])){
                $_SESSION['ad_province']=$row['ad_province'];
            }
            if (isset($row['ad_post_code'])){
                $_SESSION['ad_post_code']=$row['ad_post_code'];
            }
            if (isset($row['ad_country'])){
                $_SESSION['ad_country']=$row['ad_country'];
            }
            if (isset($row['ph_type'])){
                $_SESSION['ph_type']=$row['ph_type'];
            }
            if (isset($row['ph_number'])){
                $_SESSION['ph_number']=$row['ph_number'];
            }
            if (isset($row['em_type'])){
                $_SESSION['em_type']=$row['em_type'];
            }
            if (isset($row['em_email'])){
                $_SESSION['em_email']=$row['em_email'];
            }
            if (isset($row['we_type'])){
                $_SESSION['we_type']=$row['we_type'];
            }
            if (isset($row['we_url'])){
                $_SESSION['we_url']=$row['we_url'];
            }
            if (isset($row['no_note'])){
                $_SESSION['no_note']=$row['no_note'];
            }

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

function validateContactEdit(){
	$err_msgs = array();
	if (!isset($_POST['list_select'])){
		$err_msgs[] = "One of the lists must be selected";
	} 
	return $err_msgs;
}
?>
