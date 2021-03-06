<?php
  $rankRequired = 1;
  $userRank = (isset($_SESSION['rank'])) ? $_SESSION['rank'] : 9999;
  Session::checkPoint($rankRequired,$userRank);
  $dieMessage = '<a href="'.$_SERVER['PHP_SELF'].'">Click to go back to user registration page</a><br />';

  if( !isset($_GET['edit']) && !isset($_GET['delete']) ) {
	echo '	<div class="container-fluid">
	 <table class="table table-striped table-bordered table-hover">
	  <thead>
	   <tr>
	    <th width="50px">ID</th>
	    <th width="200px">User name</th>
	    <th width="300px">Name</th>
	    <th width="50px">Rank</th>
	    <th width="70px">&nbsp;</th>
	    <th width="80px">&nbsp;</th>
	   </tr>
	  </thead>';
	$resultArray = $db_connect->dbQuery("users");
	for( $i=0; isset($resultArray[$i]); $i++ ){
	  echo '<tbody>
	   <tr>
	    <td>'.$resultArray[$i]['id'].'</td>
	    <td>'.$resultArray[$i]['nickname'].'</td>
	    <td>'.$resultArray[$i]['name'].'</td>
	    <td>'.$resultArray[$i]['rank'].'</td>
	    <td><a href="?page=login/user_admin.php&edit='.$resultArray[$i]['id'].'"><button type="button" class="btn btn-warning">Edit</button></td>
	    <td><a href="?page=login/user_admin.php&delete='.$resultArray[$i]['id'].'"><button type="button" class="btn btn-danger">Delete</button></td>
	   </tr>';
	 }
	echo '	  </tbody>
		 </table>
		 <a href="?page=login/register.php"><button class="btn btn-success">+New user</button></a>
		 <br />';
}

if( isset($_GET['edit']) ) {
  $id = htmlspecialchars($_GET['edit']);
  $query = "id='".$id."'";
  $result = $db_connect->dbQuery("users",$query);
  $rank = $db_connect->dbQuery("user_ranks");
  echo '
  <h3> Editing user <strong>'.$result[0]['name'].'</strong> (<i>id = <strong>'.$result[0]['id'].'</strong></i>)</h3>
  <form class="form-horizontal" method="post" role="form" action="?page=login/edit_user.php">
     <input type="hidden" name="id" value="'.$result[0]['id'].'">
     <div class="form-group">
       <label class="control-label col-sm-2" for="username">User name</label>
       <div class="col-sm-10"><input class="form-control" id="username" name="username" placeholder="User name" value="'.$result[0]['nickname'].'" /></div>
     </div>
     <div class="form-group">
       <label class="control-label col-sm-2" for="name">Name</label>
       <div class="col-sm-10"><input class="form-control" id="name" name="name" placeholder="Name" value="'.$result[0]['name'].'" /></div>
     </div>
     <div class="form-group">
       <label class="control-label col-sm-2" for="rank">Access level</label>
       <div class="col-sm-10"><select class="form-control" id="rank" name="rank">';
         for( $i=0; isset($rank[$i]['rank']); $i++) {
           $selected = ($rank[$i]['rank'] == $result[0]['rank']) ? ' selected' : '';
           echo '<option value="'.$rank[$i]['rank'].'"'.$selected.'>'.$rank[$i]['title'].'</option>';
         }
  echo     '</select></div>
     </div>
     <div class="col-sm-10 col-sm-offset-2">
       <div class="form-group">
          <input type="button" class="btn btn-default" value="Change Password" onClick="goBack(\'login/reset_password.php&id='.$id.'\');" />
          <input type="submit" value="Edit user" name="submit" class="btn btn-info" />
          <input type="button" class="btn btn-warning" value="Cancel" onClick="goBack(\'login/user_admin.php\');" />
       </div>
     </div>
  </form>
  ';
}

if( isset($_GET['delete']) ) {
  $id = htmlspecialchars($_GET['delete']);
  $query = "id='".$id."'";
  $result = $db_connect->dbQuery("users",$query);
  echo '
    <div class="text-center">
    <span class="text-warning bg-warning"><h3>Are you sure you want to delete user <strong>'.$result[0]['name'].'</strong>?</h3></span>
    <p>
      <form action="admin.php?page=login/delete.php" method="post">
        <input type="hidden" name="deleteId" value="'.$result[0]['id'].'">
        <input type="submit" class="btn btn-danger" value="Yes, delete it straight away">
      </form>
    </p>
    <p><a href="?page=login/user_admin.php"><button type="button" class="btn btn-success">No, it was a mistake, take me back.</button></a></p>
  ';
}
?>