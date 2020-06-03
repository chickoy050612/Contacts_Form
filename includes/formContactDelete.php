<!-- 
1. purpose: This file is to implement the functionality to perform a soft delete operation on a contact. 
This is to be done when a user selects a contact on the contact list and clicks the ‘Delete’ button. 
After the deletion has been performed the user is to be returned to the contact list. 
The page prompts the user to confirm the delete operation before performing it. 
2. authors: group 6
-->
<?php
function formContactDelete($db_conn){
    
    $ct_id=$_POST['list_select'];
	$qry = 'SELECT * from contact 
			left join contact_address on ct_id = ad_ct_id 
			left join contact_phone on ct_id = ph_ct_id
			left join contact_email on ct_id = em_ct_id
			left join contact_web on ct_id = we_ct_id
			left join contact_note on ct_id = no_ct_id    
			where ct_id=?;';
    $stmt = $db_conn->prepare($qry);
	if (!$stmt){
		echo "<p>Error in select prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
		exit(1);
	}
	
	$status = $stmt->execute($ct_id);
	if ($status){
		if ($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
           
            if(!empty($row['ad_id'])){
                $qry1 = "DELETE FROM contact_address where ad_ct_id=?;";
				$stmt = $db_conn->prepare($qry1);
				if (!$stmt){
					echo "<p>Error in address prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
					exit(1);
				}
				$status = $stmt->execute($ct_id);
				if (!$status){
					echo "<p>Error in address execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
					exit(1);
				}
			}
			
            if(!empty($row['ph_id'])){
                $qry1 = "DELETE FROM contact_phone where ph_ct_id=?;";
      			$stmt = $db_conn->prepare($qry1);
				if (!$stmt){
					echo "<p>Error in phone prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
					exit(1);
				}
				$status = $stmt->execute($ct_id);
				if (!$status){
					echo "<p>Error in phone execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
					exit(1);
				}
			}
			
            if(!empty($row['em_id'])){
                $qry1 = "DELETE FROM contact_email where em_ct_id=?;";
              	$stmt = $db_conn->prepare($qry1);
				if (!$stmt){
					echo "<p>Error in email prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
					exit(1);
				}
				$status = $stmt->execute($ct_id);
				if (!$status){
					echo "<p>Error in email execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
					exit(1);
				}
            }
            if(!empty($row['we_id'])){
                $qry1 = "DELETE FROM contact_web where we_ct_id=?;";
             	$stmt = $db_conn->prepare($qry1);
				if (!$stmt){
					echo "<p>Error in web prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
					exit(1);
				}
				$status = $stmt->execute($ct_id);
				if (!$status){
					echo "<p>Error in web execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
					exit(1);
				}
			}
			
            if(!empty($row['no_id'])){
                $qry1 = "DELETE FROM contact_note where no_ct_id=?;";
              	$stmt = $db_conn->prepare($qry1);
				if (!$stmt){
					echo "<p>Error in note prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
					exit(1);
				}
				$status = $stmt->execute($ct_id);
				if (!$status){
					echo "<p>Error in note execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
					exit(1);
				}
            }
            
	    	if(!empty($row['ct_id'])){
                $qry1 = "DELETE FROM contact where ct_id=?;";
                $stmt = $db_conn->prepare($qry1);
				if (!$stmt){
					echo "<p>Error in contact prepare: ".$db_conn->errorCode()."</p>\n<p>Message ".implode($db_conn->errorInfo())."</p>\n";
					exit(1);
				}
				$status = $stmt->execute($ct_id);
				if (!$status){
					echo "<p>Error in contact execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
					exit(1);
				}
            }
			
			$_SESSION['mode']='Display';
            formContactDisplay();
	    
        }
         else {
            echo "<div>\n";
            echo "<p>No contacts to display</p>\n";
            echo "</div>\n";
        }
    } 
	else {
        echo "<p>Error in select execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
        exit(1);
    }
}

function validateContactDelete(){
	$err_msgs = array();
	if (!isset($_POST['list_select'])){
		$err_msgs[] = "One of the lists must be selected";
	} 
	return $err_msgs;
}
?>

