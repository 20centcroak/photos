<?php
	require_once 'php/usr.php';
	require_once 'php/UserDoor.php';
	require_once 'php/formChecker.php';
	session_start();
	$name = $_SESSION["user"];
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-4">
      <h1>Gallery settings</h1>
      <div class="card">
        <div class="card-header">
          <p>Change your settings</p>
        </div>
        <div class="card-content">
          <form class="form-signin" onsubmit="return false;">
            <button id="submitButton" class="btn btn-primary btn-xs pull-right save-btn" type="submit">save modifications</button>
            <div class="input-group">
              <label for="inputTitle">gallery title</label>
              <input type="text" name="inputTitle" id="inputTitle" class="form-control" aria-describedby="basic-addon2" oninput="update()" >
            </div>
            <label for="inputCols">columns for thumbnails</label><br>
            <div class="btn-group" data-toggle="buttons" id="colGroup" role="group" aria-label="group of buttons">
              <label class="btn btn-default" data-col="3" onclick="update(this)">
                <input type="radio" id="3cols" autocomplete="off" >
                  <span class="sr-only">3 cols</span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                </input>
              </label>
              <label class="btn btn-default" data-col="4" onclick="update(this)">
                <input type="radio" id="4cols" autocomplete="off" >
                  <span class="sr-only">4 cols</span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                </input>
              </label>
              <label class="btn btn-default" data-col="5" onclick="update(this)" >
                <input type="radio" id="5cols" autocomplete="off" >
                  <span class="sr-only">5 cols</span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                </input>
              </label>
              <label class="btn btn-default" data-col="6" onclick="update(this)">
                <input type="radio" id="6cols" autocomplete="off" >
                  <span class="sr-only">6 cols</span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                  <span class="icon-col"></span>
                </input>
              </label>
            </div>
            <div class="input-group">
              <label for="picker">background color</label><br>
              <input type="text" name="picker" id="picker" class="form-control" aria-describedby="basic-addon2">
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <!-- aperçu de la galerie avec rendu dynamique des modifications -->
      <h1>Preview</h1>
      <div id="preview">
        <div class="container-fluid text-center">    
          <h1 id="title"></h1>
          <div  id="grid" class="row"></div>
        </div>
      </div>
    </div>
  </div>
</div>