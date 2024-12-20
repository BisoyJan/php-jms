<!DOCTYPE html>
<html lang="en">

<?php
include('header.php');
include('session.php');

// Handle Add or Update Department
if (isset($_POST['save_department'])) {
    $department_id = $_POST['department_id'];
    $department_name = $_POST['new_department'];

    if ($department_id) {
        // Update department
        $conn->query("UPDATE dapartment SET depart = '$department_name' WHERE department_id = '$department_id'");
        echo "<script>
         alert('Department updated successfully!');
         window.location = 'department.php';
      </script>";
    } else {
        // Add department
        $conn->query("INSERT INTO dapartment (depart) VALUES ('$department_name')");
        echo "<script>
         alert('Department added successfully!');
         window.location = 'department.php';
      </script>";
    }
}

// Handle Delete Department
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM dapartment WHERE department_id = '$id'");
    echo "<script>
      alert('Department deleted successfully!');
      window.location = 'department.php';
   </script>";
}

// Fetch all departments
$departments = $conn->query("SELECT * FROM dapartment ORDER BY depart ASC");
?>

<body>
    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container"></div>
        </div>
    </div>
    <header class="jumbotron subhead" id="overview">
        <div class="container">
            <h1>Settings - Organizer</h1>
            <p class="lead">Pageant Tabulation System</p>
        </div>
    </header>

    <div class="container">
        <div class="col-lg-12">
            <a href="edit_tabulator.php" class="btn btn-danger"><strong>TABULATOR SETTINGS &raquo;</strong></a>
            <hr />
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Department Management</strong></h3>
                </div>
                <div class="panel-body">

                    <!-- Add / Edit Department -->
                    <form method="POST" action="" class="form-inline">
                        <input type="hidden" name="department_id" id="department_id">
                        <div class="form-group">
                            <label for="new_department">Department Name: </label>
                            <input type="text" name="new_department" id="new_department" class="form-control"
                                placeholder="Enter department" required>
                        </div>
                        <button type="submit" name="save_department" class="btn btn-success">Save</button>
                        <button type="button" id="cancel_edit" class="btn btn-secondary" style="display: none;"
                            onclick="cancelEdit()">Cancel</button>
                    </form>
                    <hr />

                    <!-- Display Departments -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            while ($row = $departments->fetch()) {
                                echo "<tr>";
                                echo "<td>" . $count++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['depart']) . "</td>";
                                echo "<td>
                           <button class='btn btn-primary btn-sm' onclick='editDepartment(" . $row['department_id'] . ", `" . htmlspecialchars($row['depart']) . "`)'>Edit</button>
                           <a href='settings.php?delete_id=" . $row['department_id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                        </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <!-- Scripts -->
    <script src="javascript/jquery1102.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script>
        function editDepartment(id, name) {
            // Populate the input field with selected department data
            document.getElementById('department_id').value = id;
            document.getElementById('new_department').value = name;

            // Show the cancel button
            document.getElementById('cancel_edit').style.display = 'inline-block';
        }

        function cancelEdit() {
            // Clear input fields
            document.getElementById('department_id').value = '';
            document.getElementById('new_department').value = '';

            // Hide the cancel button
            document.getElementById('cancel_edit').style.display = 'none';
        }
    </script>
</body>

</html>
