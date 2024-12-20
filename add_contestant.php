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
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Add Contestant</h3>
                </div>
                <div class="panel-body">
                  <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="sub_event_id" value="<?php echo $sub_event_id; ?>" />
                    <input type="hidden" name="se_name" value="<?php echo $se_name; ?>" />

                    <div class="form-group">
                      <label for="contestantCtr"><strong>Contestant No.:</strong></label>
                      <input type="number" id="contestantCtr" name="contestant_ctr" class="form-control"
                        value="<?php echo $cont_row['contestant_ctr']; ?>" required
                        onblur="checkControlNumber(this.value, '<?php echo $sub_event_id; ?>')" />
                      <small id="contestantCtrError" style="color: red; display: none;">
                        This control number is already taken!
                      </small>
                    </div>

                    <div class="form-group">
                      <label for="fullname"><strong>Contestant Name:</strong></label>
                      <input type="text" id="fullname" name="fullname" placeholder="Enter Name" class="form-control"
                        required />
                    </div>

                    <div class="form-group">
                      <label for="category"><strong>Category:</strong></label>
                      <select id="category" name="contestant_categories" class="form-control" required>
                        <option value="Ms">Ms</option>
                        <option value="Mr">Mr</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="department"><strong>Department:</strong></label>
                      <select id="department" name="contestant_departments" class="form-control" required>
                        <?php
                        $departments = $conn->query("SELECT * FROM dapartment");
                        while ($row = $departments->fetch()) {
                          echo "<option value='{$row['department_id']}'>{$row['department']}</option>";
                        }
                        ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="imageUpload"><strong>Upload Image:</strong></label>
                      <input type="file" id="imageUpload" name="image" accept="image/*" class="form-control" required />
                    </div>

                    <div class="form-group text-right">
                      <a href="sub_event_details_edit.php?sub_event_id=<?php echo $sub_event_id; ?>&se_name=<?php echo $se_name; ?>"
                        class="btn btn-default">Back</a>
                      <button type="submit" name="add_contestant" class="btn btn-primary">Save</button>
                    </div>
                  </form>
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

  function generateRandomNumber($length = 7)
  {
    $min = pow(10, $length - 1);
    $max = pow(10, $length) - 1;
    return rand($min, $max);
  }

  if (isset($_POST['add_contestant'])) {
    $rand_code = generateRandomNumber();
    $se_name = $_POST['se_name'];
    $sub_event_id = $_POST['sub_event_id'];
    $contestant_ctr = $_POST['contestant_ctr'];
    $fullname = $_POST['fullname'];
    $category = $_POST['category'];
    $department = $_POST['department'];

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
        $conn->query("INSERT INTO contestants (fullname, image, subevent_id, contestant_ctr, rand_code, category, department_id) 
                    VALUES ('$fullname', '$newImageName', '$sub_event_id', '$contestant_ctr', '$rand_code', '$category', '$department')");

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
