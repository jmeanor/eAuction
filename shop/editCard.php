<?php 
    // Title:       shop/editCard.php
    // Desc:        Displays user credit cards for either adding, changing, or deleting 
    // Date:        March 22, 2014
    // Version:     1.0
    // Author:      John Meanor
    require_once("../inc/functions.php");
	require_once("../inc/header.php");

    if (!empty($_GET['userid'])) {
	  $data = getProfileData($_GET['userid'], $db);
	}
    else {
      $data = getProfileData($_SESSION['user']['user_id'], $db);
    }
	
	$submitted_type="";
	$submitted_number="";
	$submitted_expiration="";
	$submitted_name="";

  	if(isset($_POST['phase']))	
		$phase = $_POST['phase'];
	else
		$phase = '';
	
	if($phase == 'add')
	{
		// variable used to determine if form is okay to submit to API
        $fields_ok = true;
		
        if(empty($_POST['number'])) 
        { 
            $_POST['message']['content'] = "Please enter a valid 16-digit credit card number using only numbers."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 
		
        if(empty($_POST['expiration'])) 
        { 
            $_POST['message']['content'] = "Please enter a valid card expiration date (mm/yy)."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        } 
        
         if(empty($_POST['name'])) 
        { 
            $_POST['message']['content'] = "Please enter a valid card name using only letters."; 
            $_POST['message']['type'] = "danger";
            $fields_ok = false;
        }         

        if($fields_ok)
		{
            
			$result = addCard($data['user_data']['user_id'], $_POST['type'], $_POST['number'], $_POST['expiration'], $db);

			if ($result['success'])
			{
				$_POST['message']['content'] = "Card added successfully!"; 
				$_POST['message']['type'] = "success";
			}
			else 
			{
			// Fill in the username field that the user tried to login with
				$submitted_type=$_POST['type'];
				$submitted_number=$_POST['number'];
				$submitted_expiration=$_POST['expiration'];
				$submitted_name=$_POST['name'];
			}
		}
		
		$phase = '';
	}
	elseif($phase == 'delete')
	{
		$card_id = $_POST['card_id'];
		$query = "DELETE FROM credit_cards WHERE card_id=:card_id";
		$query_params = array(':card_id' => $card_id);
		try 
		{ 
			// Execute the query against the database 
			$stmt = $db->prepare($query); 
			$result = $stmt->execute($query_params); 
			$success = true;
		} 
		catch(PDOException $ex) 
		{ 
			$success = false;
		}
		if($success)
		{
			$_POST['message']['content'] = "Card deleted successfully!"; 
			$_POST['message']['type'] = "success";
		}
		else
		{
			$_POST['message']['content'] = "Cannot delete a card that has been used to bid on items!"; 
			$_POST['message']['type'] = "danger";
		}
		
		$phase = '';
	}
	
	$card_data = getCards($data['user_data']['user_id'], $db);
	
	if($phase == '')
	{
?>
      <div class="container">
        <div class="row">
          <h1></h1>
          <?php if (isset($_POST['message']) && $_POST['message']['type'] == "danger") 
				{
					echo '<div class="alert alert-danger">'.$_POST['message']['content'].'</div>'; 
				}
				elseif(isset($_POST['message']) && $_POST['message']['type'] == 'success')
				{
					echo '<div class="alert alert-success">'.$_POST['message']['content'].'</div>'; 
				}
?>
        </div>
		<form id="newcard" class="form-signin" action="editCard.php" method="POST">
		<input type='hidden' id='phase' name='phase' value='' />
		<input type='hidden' id='card_id' name='card_id' value='' />
		<div class="row">
			<div class="col-sm-12">
				<?php if ($card_data['success'] == false) 
					  {
						echo '<div class="alert alert-info">There are no credit cards on file.</div>'; 
					  }
					  else 
					  { ?>
				<table class="table table-hover">
					<tr>
						<th>Card Type</th>
						<th>Card Number</th>
						<th>Expiration</th>
						<th>Name on Card</th>
						<th></th>
					</tr>
					<?php foreach($card_data['card_data'] as $row) 
						  { ?>
					<tr>
						<td><?php echo $row['card_type']; ?></td>
						<td><?php echo $row['card_number']; ?></td>
						<td><?php echo $row['expiration']; ?></td>
						<td><?php echo $data['user_data']['name']; ?></td>
						<td><button  class="btn btn-sm btn-primary btn-block" type="submit" onclick="document.getElementById('phase').value = 'delete'; document.getElementById('card_id').value = '<?php echo $row['card_id'] ?>';">Delete</button></tr>
					<?php }
					  }?>
				</table>
			</div>
		</div>
		
		<div class="col-sm-4">
          <h2 class="form-signin-heading">Add a Card</h2>
			<select name="type">
			  <option value="Visa">Visa</option>
			  <option value="MasterCard">MasterCard</option>
			  <option value="American Express">American Express</option>
			  <option value="Discover">Discover</option>
			</select> 
			</br>
			<input class="form-control" type="text" name="number" maxlength="16" size="16" placeholder="Card Card Number (numbers only, 16 digits)" value="<?php echo $submitted_number?>" />
            <input class="form-control" type="text" name="expiration" placeholder="Card Expiration (mm/yy)" value="<?php echo $submitted_expiration?>" /> 
            <input class="form-control" type="text" name="name" placeholder="Name on Card" value="<?php echo $data['user_data']['name'] ?>"/>
            <br />
            <button class="btn btn btn-primary btn-block" type="submit" onclick="document.getElementById('phase').value = 'add';" >Add Card</button>
        </form>
      </div>
	</div>
<?php }

require_once("../inc/footer.php"); ?>