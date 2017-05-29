<?php
	require_once 'php/usr.php';
	require_once 'php/UserDoor.php';
	require_once 'php/formChecker.php';
	session_start();
	$name = $_SESSION["user"];
	$door = UserDoor::userDoorFactory($name);
	if ($door==null) header("Location:https://photos.croak.fr/signin.php");
	$user = $door->getUser();
	$email = $user->email();
	$alias = $user->alias();
	$gravatar = $user->gravatar();
	$title = $user->title();
	$created = date('F Y', $user->creationDate());
	$update = date('m-d-Y', $user->lastUpdate());
?>

<h1>Profile</h1>
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <p>Edit your profile</p>
      </div>
      <div class="card-content">
        <form class="form-signin" onsubmit="return false;">
          <div class="row">
            <div class="col-md-4">
              <div class="input-group">
                <label for="inputName">user name</label>
                <input type="text" name="inputName" id="inputName" class="form-control" aria-describedby="basic-addon2" value="<?php echo "$name"; ?>" disabled="true">
              </div>
            </div>
            <div class="col-md-4">
              <div class="input-group">
                <label for="inputAlias">alias</label>
                <input type="text" name="inputAlias" id="inputAlias" class="form-control" aria-describedby="basic-addon2" value="<?php echo "$alias"; ?>" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="input-group">
                <label for="inputEmail">e-mail</label>
                <input type="email" name="inputEmail" id="inputEmail" class="form-control" aria-describedby="basic-addon2" value="<?php echo "$email"; ?>"required>
              </div>
            </div>
          </div>
          <p class="pass-change"><a href="resetPassword.php">change password</a></p>
          <button id="submitButton" onClick="croakAdmin.updateUser()" class="btn btn-primary pull-right">update profile</button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-avatar">
        <img id="gravatar" width="130px" height="130px" src="<?php echo "$gravatar"; ?>">
      </div>
      <div class="card-profile" id="card-content">
        <h1><?php echo"$title";?></h1>
        <p><i>by</i></p>
        <h2><?php echo"$alias";?></h2>
        <h3>member since <?php echo"$created";?></h3>
        <p>&nbsp</p>
      </div>
    </div>
  </div>
</div>