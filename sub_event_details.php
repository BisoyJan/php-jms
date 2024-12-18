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
        <form method="POST" id="settingsForm">
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
            var count = 1;

            // Add Contestants
            $('#add-contestant').click(function (e) {
                e.preventDefault();
                count++;
                $('#contestant-form .text-box:last').after(`<p class="text-box"><label>Contestant No. <span class="box-number">${count}</span></label><input type="text" name="contestants[]" placeholder="Contestant Fullname" required /></p>`);
            });

            // Add Judges
            $('#add-judge').click(function (e) {
                e.preventDefault();
                count++;
                $('#judge-form .text-box:last').after(`<p class="text-box"><label>Judge No. <span class="box-number">${count}</span></label><input type="text" name="judges[]" placeholder="Judge Fullname" required /></p>`);
            });

            // Add Criteria and Validate Points
            let totalPoints = 0;

            function updateTotalPoints() {
                totalPoints = 0;
                $('.criteria-points').each(function () {
                    totalPoints += parseInt($(this).val()) || 0;
                });
                $('#total-points').text(totalPoints);

                if (totalPoints > 100) {
                    alert('Total points cannot exceed 100%.');
                    return false;
                }
                return true;
            }

            $('#criteria-form').on('input', '.criteria-points', updateTotalPoints);

            $('#add-criteria').click(function (e) {
                e.preventDefault();
                count++;
                if (count <= 10) {
                    $('#criteria-form .text-box:last').after(`<p class="text-box"><label>Criteria No. <span class="box-number">${count}</span></label><input type="text" name="criteria[]" placeholder="Description" required /><label>Points:</label><input type="number" name="points[]" min="0" max="100" class="criteria-points" required /></p>`);
                } else {
                    alert('You can only add up to 10 criteria');
                }
            });

            $('#settingsForm').submit(function (e) {
                if (!updateTotalPoints() || totalPoints > 100) {
                    e.preventDefault();
                    alert('Total points must be 100 or less.');
                }
            });
        });
    </script>

    <?php
    function generateRandomCode($length = 6)
    {
        $characters = "abcdefghijkmnopqrstuvwxyz0123456789";
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }

    function generateRandomNumber($length = 7)
    {
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        return rand($min, $max);
    }

    if (isset($_POST['save_settings'])) {
        $sub_event_id = $_POST['sub_event_id'];
        $se_name = $_POST['se_name'];

        $totalPoints = array_sum($_POST['points']);
        if ($totalPoints > 100) {
            echo "<script>alert('Total points for criteria must not exceed 100%');</script>";
            exit();
        }

        // Save Contestants
        foreach ($_POST['contestants'] as $index => $contestant) {
            $rand_code = generateRandomNumber();
            $conn->query("INSERT INTO contestants (fullname, subevent_id, contestant_ctr, rand_code) VALUES ('$contestant', '$sub_event_id', '$index', '$rand_code')");
        }

        // Save Judges
        foreach ($_POST['judges'] as $index => $judge) {
            $rand_code = generateRandomCode();
            $conn->query("INSERT INTO judges (fullname, subevent_id, judge_ctr, code) VALUES ('$judge', '$sub_event_id', '$index' , '$rand_code')");
        }

        // Save Criteria
        foreach ($_POST['criteria'] as $index => $criteria) {
            $points = $_POST['points'][$index];
            $conn->query("INSERT INTO criteria (description, subevent_id, criteria_ctr, points) VALUES ('$criteria', '$sub_event_id', '$index', '$points')");
        }

        echo "<script>window.location = 'sub_event_details_edit.php?sub_event_id=$sub_event_id&se_name=$se_name';</script>";
    }
    ?>
</body>

</html>
