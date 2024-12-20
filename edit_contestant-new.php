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

                    <li>Edit Contestant</li>

                </ul>
            </div>



            <form method="POST" enctype="multipart/form-data">
                <input value="<?php echo $sub_event_id; ?>" name="sub_event_id" type="hidden" />
                <input value="<?php echo $se_name; ?>" name="se_name" type="hidden" />
                <input value="<?php echo $contestant_id; ?>" name="contestant_id" type="hidden" />

                <table align="center" style="width: 40% !important;">
                    <?php
                    $cont_query = $conn->query("SELECT * FROM contestants WHERE contestant_id='$contestant_id'") or die(mysql_error());
                    while ($cont_row = $cont_query->fetch()) { ?>
                        <tr>
                            <td>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Edit Contestant</h3>
                                    </div>
                                    <div class="panel-body">
                                        <form method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="sub_event_id" value="<?php echo $sub_event_id; ?>" />
                                            <input type="hidden" name="se_name" value="<?php echo $se_name; ?>" />

                                            <div class="form-group">
                                                <label for="contestantCtr"><strong>Contestant No.:</strong></label>
                                                <input type="number" id="contestantCtr" name="contestant_ctr"
                                                    class="form-control" value="<?php echo $cont_row['contestant_ctr']; ?>"
                                                    required
                                                    onblur="checkControlNumber(this.value, '<?php echo $sub_event_id; ?>')" />
                                                <small id="contestantCtrError" style="color: red; display: none;">
                                                    This control number is already taken!
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <label for="fullname"><strong>Contestant Name:</strong></label>
                                                <input type="text" id="fullname" name="fullname" placeholder="Enter Name"
                                                    class="form-control" required />
                                            </div>

                                            <div class="form-group">
                                                <label for="category"><strong>Category:</strong></label>
                                                <select id="category" name="contestant_categories" class="form-control"
                                                    required>
                                                    <option value="Ms">Ms</option>
                                                    <option value="Mr">Mr</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="department"><strong>Department:</strong></label>
                                                <select id="department" name="contestant_departments" class="form-control"
                                                    required>
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
                                                <input type="file" id="imageUpload" name="image" accept="image/*"
                                                    class="form-control" required />
                                            </div>
                                        <?php } ?>
                                        <div class="form-group text-right">
                                            <a href="sub_event_details_edit.php?sub_event_id=<?php echo $sub_event_id; ?>&se_name=<?php echo $se_name; ?>"
                                                class="btn btn-default">Back</a>
                                            <button type="submit" name="add_contestant"
                                                class="btn btn-primary">Save</button>
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
    if (isset($_POST['edit_contestant'])) {
        $se_name = $_POST['se_name'];
        $sub_event_id = $_POST['sub_event_id'];
        $contestant_id = $_POST['contestant_id'];
        $contestant_ctr = $_POST['contestant_ctr'];
        $department = $_POST['deparment'];
        $category = $_POST['category'];
        $fullname = $_POST['fullname'];

        $check_query = $conn->query("SELECT * FROM contestants WHERE contestant_ctr='$contestant_ctr' AND subevent_id='$sub_event_id' AND contestant_id != '$contestant_id'");
        if ($check_query->rowCount() > 0) {
            echo "<script>alert('Control number already taken. Please choose another.');</script>";
        } else {
            $update_query = "UPDATE contestants SET fullname='$fullname', contestant_ctr='$contestant_ctr' , category='$category', deparment_id='$department'";

            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $imageTmpPath = $_FILES['image']['tmp_name'];
                $imageName = $_FILES['image']['name'];
                $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
                $cleanFullname = preg_replace('/[^a-zA-Z0-9_-]/', '_', $fullname . $contestant_ctr);
                $newImageName = $cleanFullname . '.' . $imageExtension;

                $uploadFolder = 'uploads/contestants/';
                if (!is_dir($uploadFolder)) {
                    mkdir($uploadFolder, 0755, true);
                }

                $imagePath = $uploadFolder . $newImageName;
                if (move_uploaded_file($imageTmpPath, $imagePath)) {
                    $update_query .= ", image='$newImageName'";
                }
            }
            $update_query .= " WHERE contestant_id='$contestant_id'";
            $conn->query($update_query);

            echo "<script>
        window.location = 'sub_event_details_edit.php?sub_event_id=$sub_event_id&se_name=$se_name';
        alert('Contestant $fullname updated successfully!');
      </script>";
        }
    }
    ?>

    <?php include('footer.php'); ?>
    <script src="../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>

</html>
