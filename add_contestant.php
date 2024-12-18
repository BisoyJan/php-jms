<!DOCTYPE html>
<html lang="en">

<?php
include('header.php');
include('session.php');


$sub_event_id = $_GET['sub_event_id'];
$se_name = $_GET['se_name'];


?>

<body>
  <!-- Navbar
    ================================================== -->
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">


      </div>
    </div>
  </div>
  <header class="jumbotron subhead" id="overview">
    <div class="container">
      <h1><?php echo $se_name; ?> Settings</h1>
      <p class="lead">Pageant Tabulation System</p>
    </div>
  </header>


  <div class="container">

    <div class="span12">



      <br />
      <div class="col-md-12">
        <ul class="breadcrumb">

          <li><a href="selection.php">User Selection</a></li>

          <li><a href="home.php">List of Events</a></li>

          <li><a
              href="sub_event_details_edit.php?sub_event_id=<?php echo $sub_event_id; ?>&se_name=<?php echo $se_name; ?>"><?php echo $se_name; ?>
              Settings</a></li>

          <li>Add Contestant</li>

        </ul>
      </div>



      <form method="POST" enctype="multipart/form-data">
        <input value="<?php echo $sub_event_id; ?>" name="sub_event_id" type="hidden" />
        <input value="<?php echo $se_name; ?>" name="se_name" type="hidden" />

        <table align="center" style="width: 40% !important;">
          <tr>
            <td>
              <div style="width: 100% !important;" class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Add Contestant</h3>
                </div>
                <div class="panel-body">
                  <table align="center">
                    <tr>
                      <td>
                        <strong>Contestant no. :</strong> <br />
                        <input type="number" name="contestant_ctr" class="form-control"
                          value="<?php echo $cont_row['contestant_ctr']; ?>" required
                          onblur="checkControlNumber(this.value, '<?php echo $sub_event_id; ?>')" />
                        <small id="contestantCtrError" style="color: red; display: none;">This control number is already
                          taken!</small>
                        </select>
                      </td>
                      <td>&nbsp;&nbsp;&nbsp;</td>
                      <td>
                        <strong>Contestant Name:</strong> <br />
                        <input name="fullname" placeholder="Enter Name" type="text" class="form-control" required />
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">
                        <strong>Upload Image:</strong> <br />
                        <input name="image" type="file" accept="image/*" class="form-control" required />
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" align="right">
                        <a href="sub_event_details_edit.php?sub_event_id=<?php echo $sub_event_id; ?>&se_name=<?php echo $se_name; ?>"
                          class="btn btn-default">Back</a>
                        <button name="add_contestant" class="btn btn-primary">Save</button>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </form>


    </div>

  </div>
  </td>
  </tr>
  </table>


  </div>

  </div>

  <script>
    function checkControlNumber(value, subEventId) {
      if (value) {
        fetch(`check_control_number.php?contestant_ctr=${value}&sub_event_id=${subEventId}`)
          .then(response => response.json())
          .then(data => {
            const errorElement = document.getElementById('contestantCtrError');
            if (data.taken) {
              errorElement.style.display = 'block';
            } else {
              errorElement.style.display = 'none';
            }
          });
      }
    }
  </script>
  <?php

  if (isset($_POST['add_contestant'])) {
    $se_name = $_POST['se_name'];
    $sub_event_id = $_POST['sub_event_id'];
    $contestant_ctr = $_POST['contestant_ctr'];
    $fullname = $_POST['fullname'];

    // Handle image upload
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
        $conn->query("INSERT INTO contestants (fullname, image, subevent_id, contestant_ctr) 
                    VALUES ('$fullname', '$newImageName', '$sub_event_id', '$contestant_ctr')");

        ?>
        <script>
          window.location = 'sub_event_details_edit.php?sub_event_id=<?php echo $sub_event_id; ?>&se_name=<?php echo $se_name; ?>';
          alert('Contestant <?php echo $fullname; ?> added successfully!');
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

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>

</html>
