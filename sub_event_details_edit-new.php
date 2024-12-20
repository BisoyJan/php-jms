<!DOCTYPE html>
<html lang="en">
<?php
include('header.php');
include('session.php');
error_reporting(0);

$sub_event_id = $_GET['sub_event_id'];
$se_name = $_GET['se_name'];

// Redirect if sub_event_id has existing data
foreach (['contestants', 'judges', 'criteria'] as $table) {
    $query = $conn->query("SELECT * FROM $table WHERE subevent_id='$sub_event_id'");
    if ($query->rowCount() > 0) {
        echo "<script>window.location = 'sub_event_details_edit.php?sub_event_id=$sub_event_id&se_name=$se_name';</script>";
        exit();
    }
}
?>

<head>
    <style>
        #footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: lightyellow;
            border: 2px solid black;
            box-shadow: 3px 3px 8px #818181;
            padding: 4px;
            width: 200px;
        }

        #main {
            margin: 0 auto;
            max-width: 800px;
            border: 1px solid gray;
            padding: 10px;
        }

        .panel {
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .panel-primary {
            border-color: #337ab7;
        }

        .panel-primary>.panel-heading {
            background-color: #337ab7;
            border-color: #337ab7;
            color: #fff;
            font-weight: bold;
        }

        .panel-body {
            background-color: #f9f9f9;
            padding: 20px;
        }

        .my-form p.text-box {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 5px;
            background: #fff;
        }

        .my-form input[type="text"],
        .my-form input[type="file"],
        .my-form select {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .add-box {
            display: inline-block;
            margin-top: 10px;
        }

        .add-box.btn-success {
            background-color: #5cb85c;
            border-color: #4cae4c;
            color: #fff;
        }

        .add-box.btn-success:hover {
            background-color: #449d44;
            border-color: #398439;
        }

        label {
            font-weight: bold;
        }
    </style>

    <script src="bootstrap/js/jquery-latest.js"></script>
</head>

<body>
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <ul>
                    <li><br /><a href="home.php">Home | Organizer: <b><?php echo $name; ?></b></a></li>
                </ul>
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
        <form method="POST" id="settingsForm" enctype="multipart/form-data">
            <input type="hidden" name="se_name" value="<?php echo $se_name; ?>" />
            <input type="hidden" name="sub_event_id" value="<?php echo $sub_event_id; ?>" />

            <!-- Contestants Settings -->
            <div class="col-lg-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Contestant's Settings</h3>
                    </div>
                    <div class="panel-body">
                        <div id="contestant-form" class="my-form">
                            <p class="text-box">
                                <label>Contestant No. <span class="box-number">1</span></label>
                                <input type="text" name="contestants[]" placeholder="Contestant Fullname" required />
                                <label>Image:</label>
                                <input type="file" name="contestant_images[]" accept="image/*" required />
                                <label>Category:</label>
                                <select name="contestant_categories[]" required>
                                    <option value="Ms">Ms</option>
                                    <option value="Mr">Mr</option>
                                </select>
                                <label>Department:</label>
                                <select name="contestant_departments[]" required>
                                    <?php
                                    $departments = $conn->query("SELECT * FROM dapartment");
                                    while ($row = $departments->fetch()) {
                                        echo "<option value='{$row['department_id']}'>{$row['depart']}</option>";
                                    }
                                    ?>
                                </select>
                            </p>
                            <p><a id="add-contestant" class="add-box btn btn-sm btn-success" href="#">Add Contestant</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Judges Settings -->
            <div class="col-lg-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Judge's Settings</h3>
                    </div>
                    <div class="panel-body">
                        <div id="judge-form" class="my-form">
                            <p class="text-box">
                                <label>Judge No. <span class="box-number">1</span></label>
                                <input type="text" name="judges[]" placeholder="Judge Fullname" required />
                            </p>
                            <p><a id="add-judge" class="add-box btn btn-sm btn-success" href="#">Add Judge</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Criteria Settings -->
            <div class="col-lg-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Criteria's Settings</h3>
                    </div>
                    <div class="panel-body">
                        <div id="criteria-form" class="my-form">
                            <p class="text-box">
                                <label>Criteria No. <span class="box-number">1</span></label>
                                <input type="text" name="criteria[]" placeholder="Description" required />
                                <label>Points:</label>
                                <input type="number" name="points[]" min="0" max="100" class="criteria-points"
                                    required />
                            </p>
                            <p><a id="add-criteria" class="add-box btn btn-sm btn-success" href="#">Add Criteria</a></p>
                        </div>
                        <p><strong>Total Points:</strong> <span id="total-points">0</span>/100</p>
                    </div>
                </div>
            </div>

            <div id="footer">
                <table>
                    <tr>
                        <td><a href="home.php" class="btn btn-default">Cancel</a></td>
                        <td>&nbsp;</td>
                        <td><button name="save_settings" id="submit" type="submit" class="btn btn-primary">Save
                                Settings</button></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>

    <script>
        jQuery(document).ready(function ($) {
            var contestantCount = 1;
            var criteriaCount = 1;

            // Add Contestants
            $('#add-contestant').click(function (e) {
                e.preventDefault();
                contestantCount++;
                $('#contestant-form .text-box:last').after(`<p class="text-box"><label>Contestant No. <span class="box-number">${contestantCount}</span></label>
                                <input type="text" name="contestants[]" placeholder="Contestant Fullname" required />
                                <label>Image:</label>
                                <input type="file" name="contestant_images[]" accept="image/*" required />
                                <label>Category:</label>
                                <select name="contestant_categories[]" required>
                                    <option value="Ms">Ms</option>
                                    <option value="Mr">Mr</option>
                                </select>
                                <label>Department:</label>
                                <select name="contestant_departments[]" required>
                                    <?php
                                    $departments = $conn->query("SELECT * FROM dapartment");
                                    while ($row = $departments->fetch()) {
                                        echo "<option value='{$row['department_id']}'>{$row['depart']}</option>";
                                    }
                                    ?>
                                </select>
                                <a href="#" class="remove-box btn btn-danger btn-sm">Remove</a>
                            </p>`);
            });

            // Add Judges
            $('#add-judge').click(function (e) {
                e.preventDefault();
                const judgeCount = $('#judge-form .text-box').length + 1;
                $('#judge-form .text-box:last').after(`<p class="text-box">
                                <label>Judge No. <span class="box-number">${judgeCount}</span></label>
                                <input type="text" name="judges[]" placeholder="Judge Fullname" required />
                                <a href="#" class="remove-box btn btn-danger btn-sm">Remove</a>
                            </p>`);
            });

            // Add Criteria
            $('#add-criteria').click(function (e) {
                e.preventDefault();
                criteriaCount++;
                $('#criteria-form .text-box:last').after(`<p class="text-box">
                                <label>Criteria No. <span class="box-number">${criteriaCount}</span></label>
                                <input type="text" name="criteria[]" placeholder="Description" required />
                                <label>Points:</label>
                                <input type="number" name="points[]" min="0" max="100" class="criteria-points" required />
                                <a href="#" class="remove-box btn btn-danger btn-sm">Remove</a>
                            </p>`);
                updateTotalPoints();
            });

            // Remove Elements
            $(document).on('click', '.remove-box', function (e) {
                e.preventDefault();
                $(this).closest('.text-box').remove();
                updateTotalPoints();
            });

            // Update Total Points
            function updateTotalPoints() {
                let totalPoints = 0;
                $('.criteria-points').each(function () {
                    totalPoints += parseInt($(this).val()) || 0;
                });
                $('#total-points').text(totalPoints);
            }

            $(document).on('change', '.criteria-points', updateTotalPoints);
        });
    </script>

    <?php
    if (isset($_POST['save_settings'])) {
        $se_name = $_POST['se_name'];
        $sub_event_id = $_POST['sub_event_id'];

        // Save Contestants
        foreach ($_POST['contestants'] as $index => $contestant) {
            $category = $_POST['contestant_categories'][$index];
            $department = $_POST['contestant_departments'][$index];
            $image = $_FILES['contestant_images']['name'][$index];
            $target = "uploads/" . basename($image);

            if (move_uploaded_file($_FILES['contestant_images']['tmp_name'][$index], $target)) {
                $conn->query("INSERT INTO contestants (fullname, image, category, department, subevent_id, contestant_ctr, status, txt_code, rand_code, txtPollScore) VALUES ('$contestant', '$image', '$category', '$department', '$sub_event_id', '$index', 'Active', 'Code_$index', RAND(), 0)");
            }
        }

        // Save Judges
        foreach ($_POST['judges'] as $index => $judge) {
            $conn->query("INSERT INTO judges (fullname, subevent_id, judge_ctr, code, jtype) VALUES ('$judge', '$sub_event_id', '$index', 'JCode_$index', 'Regular')");
        }

        // Save Criteria
        foreach ($_POST['criteria'] as $index => $criterion) {
            $points = $_POST['points'][$index];
            $conn->query("INSERT INTO criteria (criteria, percentage, subevent_id, criteria_ctr) VALUES ('$criterion', '$points', '$sub_event_id', '$index')");
        }

        echo "<script>alert('Settings saved successfully!'); window.location = 'sub_event_details.php';</script>";
    }
    ?>
</body>

</html>
