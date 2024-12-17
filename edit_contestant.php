<!DOCTYPE html>
<html lang="en">

<?php
include('header.php');
include('session.php');

$sub_event_id = $_GET['sub_event_id'];
$se_name = $_GET['se_name'];
$contestant_id = $_GET['contestant_id'];
?>
<style>
  img {
    max-width: 80%;
    /* set a maximum width to prevent the image from becoming too large */
    height: auto;
    margin: 0 auto;
    display: block;
  }

  .large-centered {
    width: 50%;
    height: auto;
    margin: 0 auto;
    display: block;
  }
</style>

<body>
  <!-- Navbar -->
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container"></div>
    </div>
  </div>

  <header class="jumbotron subhead" id="overview">
    <div class="container">
      <h1><?php echo $se_name; ?> Settings</h1>
      <p class="lead">Pageant Tabulation System</p>
    </div>
  </header>

  <div class="container">
    <form method="POST" enctype="multipart/form-data">
      <input value="<?php echo $sub_event_id; ?>" name="sub_event_id" type="hidden" />
      <input value="<?php echo $se_name; ?>" name="se_name" type="hidden" />
      <input value="<?php echo $contestant_id; ?>" name="contestant_id" type="hidden" />

      <div class="col-lg-3"></div>
      <div class="col-lg-6">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Edit Contestant</h3>
          </div>

          <div class="panel-body">
            <table align="center">
              <?php
              $cont_query = $conn->query("SELECT * FROM contestants WHERE contestant_id='$contestant_id'") or die(mysql_error());
              while ($cont_row = $cont_query->fetch()) { ?>
                <tr>
                  <td>
                    Contestant No. <br />
                    <select name="contestant_ctr" class="form-control">
                      <option><?php echo $cont_row['contestant_ctr']; ?></option>
                      <?php
                      $n1 = 0;
                      while ($n1 < 12) {
                        $n1++;
                        $cont_query = $conn->query("SELECT * FROM contestants WHERE contestant_ctr='$n1' AND subevent_id='$sub_event_id'") or die(mysql_error());
                        if ($cont_query->rowCount() == 0) {
                          echo "<option>$n1</option>";
                        }
                      }
                      ?>
                    </select>
                  </td>
                  <td>&nbsp;</td>
                  <td>
                    Contestant Fullname <br />
                    <input name="fullname" type="text" class="form-control"
                      value="<?php echo $cont_row['fullname']; ?>" />
                  </td>
                </tr>
                <tr>
                  <td colspan="3">
                    Current Image:<br />
                    <img class="large-centered" src="uploads/contestants/<?php echo $cont_row['image']; ?>"
                      alt="Contestant Image" width="100" />
                  </td>
                </tr>
                <tr>
                  <td colspan="3">
                    Update Image:<br />
                    <input type="file" name="image" accept="image/*" class="form-control" />
                  </td>
                </tr>
              <?php } ?>
              <tr>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3" align="right">
                  <a href="sub_event_details_edit.php?sub_event_id=<?php echo $sub_event_id; ?>&se_name=<?php echo $se_name; ?>"
                    class="btn btn-default">Back</a>
                  &nbsp;
                  <button name="edit_contestant" class="btn btn-success">Update</button>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-3"></div>
    </form>
  </div>

  <?php
  if (isset($_POST['edit_contestant'])) {
    $se_name = $_POST['se_name'];
    $sub_event_id = $_POST['sub_event_id'];
    $contestant_id = $_POST['contestant_id'];
    $contestant_ctr = $_POST['contestant_ctr'];
    $fullname = $_POST['fullname'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
      $imageTmpPath = $_FILES['image']['tmp_name'];
      $imageName = $_FILES['image']['name'];
      $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);

      // Clean up the fullname to create a valid file name
      $cleanFullname = preg_replace('/[^a-zA-Z0-9_-]/', '_', $fullname . $contestant_ctr);
      $newImageName = $cleanFullname . '.' . $imageExtension;

      // Define the upload folder
      $uploadFolder = 'uploads/contestants/';
      if (!is_dir($uploadFolder)) {
        mkdir($uploadFolder, 0755, true);
      }

      // Move the uploaded file to the target folder
      $imagePath = $uploadFolder . $newImageName;
      if (move_uploaded_file($imageTmpPath, $imagePath)) {
        // Save data to the database
        $conn->query("UPDATE contestants SET fullname='$fullname', contestant_ctr='$contestant_ctr', image='$newImageName' WHERE contestant_id='$contestant_id'");

        ?>
        <script>
          window.location = 'sub_event_details_edit.php?sub_event_id=<?php echo $sub_event_id; ?>&se_name=<?php echo $se_name; ?>';
          alert('Contestant <?php echo $fullname; ?> updated successfully!');
        </script>
        <?php
      } else {
        echo "<script>alert('Error moving uploaded image.');</script>";
      }
    } else {
      echo "<script>alert('Error uploading image. Please try again.');</script>";
    }

  }
  ?>

  <?php include('footer.php'); ?>
  <script src="../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>

</html>
