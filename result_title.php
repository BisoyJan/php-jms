<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Results</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
      background-color: #f8f9fa;
    }

    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .table {
      margin-bottom: 0;
    }

    .table thead th {
      background-color: #007bff;
      color: white;
      border: none;
    }

    .table tbody tr:hover {
      background-color: #f1f1f1;
    }

    .table tbody td {
      vertical-align: middle;
    }

    .badge {
      font-size: 1rem;
      padding: 0.5rem 1rem;
    }

    .participant-name {
      font-weight: 600;
      color: #333;
    }

    .category-header {
      background-color: #007bff;
      color: white;
      padding: 1rem;
      border-radius: 10px 10px 0 0;
    }
  </style>
</head>

<body>
  <?php
  // Enable error reporting for debugging
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  // Include necessary files
  include('header2.php');
  include('session.php');

  // Get the event ID from the URL
  $active_sub_event = $_GET['event_id'];

  // Check if event_id is provided
  if (empty($active_sub_event)) {
    die("Event ID is missing.");
  }

  // Check if database connection is established
  if (!$conn) {
    die("Database connection failed.");
  }

  // Function to convert numbers to ordinal (1st, 2nd, 3rd, etc.)
  function ordinal($i)
  {
    $l = substr($i, -1);
    $s = substr($i, -2, -1);

    return $i . (
      (($l == 1 && $s == 1) ||
        ($l == 2 && $s == 1) ||
        ($l == 3 && $s == 1) ||
        $l > 3 ||
        $l == 0) ? 'th' : (($l == 3) ? 'rd' : (($l == 2) ? 'nd' : 'st')));
  }

  // Fetch sub-event details
  $s_event_query = $conn->query("SELECT * FROM sub_event WHERE subevent_id='$active_sub_event'") or die($conn->error);
  if ($s_event_query->rowCount() == 0) {
    die("Subevent not found.");
  }

  while ($s_event_row = $s_event_query->fetch()) {
    $MEidxx = $s_event_row['mainevent_id'];

    // Fetch main event details
    $event_query = $conn->query("SELECT * FROM main_event WHERE mainevent_id='$MEidxx'") or die($conn->error);
    if ($event_query->rowCount() == 0) {
      die("Main event not found.");
    }

    while ($event_row = $event_query->fetch()) {
      // Display event name and sub-event name
      echo "<div class='container my-5'>";
      echo "<div class='card p-4'>";
      echo "<h1 class='text-center mb-4'>" . $event_row['event_name'] . "</h1>";
      echo "<h4 class='text-center mb-4'>" . $s_event_row['event_name'] . "</h4>";
      echo "<h4 class='text-center mb-4'>Participant's Placing Results</h4>";

      // Separate rankings for Mr and Ms categories
      $categories = ['Mr', 'Ms'];
      foreach ($categories as $category) {
        // Fetch contestants for the current category
        $o_result_query = $conn->query("
            SELECT DISTINCT sr.contestant_id 
            FROM sub_results sr
            JOIN contestants c ON sr.contestant_id = c.contestant_id
            WHERE sr.mainevent_id='$MEidxx' AND sr.subevent_id='$active_sub_event' 
            AND c.category='$category'
        ") or die($conn->error);

        if ($o_result_query->rowCount() == 0) {
          echo "<div class='card mb-4'>";
          echo "<div class='category-header'>";
          echo "<h3>$category Category</h3>";
          echo "</div>";
          echo "<div class='card-body'>";
          echo "<p class='text-center'>No contestants found for this category.</p>";
          echo "</div>";
          echo "</div>";
          continue;
        }

        // Display category header
        echo "<div class='card mb-4'>";
        echo "<div class='category-header'>";
        echo "<h3>$category Category</h3>";
        echo "</div>";
        echo "<div class='card-body'>";
        echo "<table class='table table-hover'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Participant</th>";
        echo "<th>Summary of Scores</th>";
        echo "<th>Participant's Placing</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // Fetch all contestants for the current category and calculate their total rank
        $contestants = [];
        while ($o_result_row = $o_result_query->fetch()) {
          $contestant_id = $o_result_row['contestant_id'];

          // Fetch contestant details
          $cname_query = $conn->query("SELECT * FROM contestants WHERE contestant_id='$contestant_id'") or die($conn->error);
          if ($cname_query->rowCount() == 0) {
            echo "<tr><td colspan='3'>Contestant not found: $contestant_id</td></tr>";
            continue;
          }

          $cname_row = $cname_query->fetch();
          $contXXname = $cname_row['contestant_ctr'] . "." . $cname_row['fullname'];

          // Calculate total rank for the contestant
          $rank_score = 0;
          $tot_score_query = $conn->query("
              SELECT sr.total_score, sr.rank 
              FROM sub_results sr
              JOIN contestants c ON sr.contestant_id = c.contestant_id
              WHERE sr.contestant_id='$contestant_id'
          ") or die($conn->error);

          while ($tot_score_row = $tot_score_query->fetch()) {
            // Ensure the rank value is numeric before converting to integer
            if (is_numeric($tot_score_row['rank'])) {
              $rank_score += intval($tot_score_row['rank']);
            } else {
              // Handle non-numeric rank values (e.g., log an error or skip)
              echo "<!-- Debug: Invalid rank value for contestant $contestant_id: " . $tot_score_row['rank'] . " -->";
            }
          }

          // Store contestant details and rank
          $contestants[] = [
            'id' => $contestant_id,
            'name' => $contXXname,
            'rank' => $rank_score
          ];
        }

        // Sort contestants by rank (ascending order)
        usort($contestants, function ($a, $b) {
          return $a['rank'] - $b['rank'];
        });

        // Display contestants with their rankings
        $rspCtr = 0;
        foreach ($contestants as $contestant) {
          $rspCtr++;
          $contestant_id = $contestant['id'];
          $contXXname = $contestant['name'];
          $rank_score = $contestant['rank'];

          // Display contestant name
          echo "<tr>";
          echo "<td><h5 class='participant-name'>$contXXname</h5></td>";

          // Fetch and display scores from judges
          echo "<td>";
          echo "<table class='table table-bordered'>";
          echo "<tr><th>Judge</th><th>Score</th><th>Rank</th></tr>";

          $tot_score_query = $conn->query("SELECT * FROM sub_results WHERE contestant_id='$contestant_id'") or die($conn->error);
          $divz = 0;
          $totx_score = 0;
          $totx_deduct = 0;

          while ($tot_score_row = $tot_score_query->fetch()) {
            $divz++;
            $totx_score += intval($tot_score_row['total_score']);
            $totx_deduct += intval($tot_score_row['deduction']);

            // Fetch judge name
            $jx_id = $tot_score_row['judge_id'];
            $jname_query = $conn->query("SELECT * FROM judges WHERE judge_id='$jx_id'") or die($conn->error);
            if ($jname_query->rowCount() == 0) {
              echo "<!-- Debug: Judge not found for ID: $jx_id -->";
            } else {
              $jname_row = $jname_query->fetch();
              echo "<tr>";
              echo "<td>" . $jname_row['fullname'] . "</td>";
              echo "<td>" . ($tot_score_row['total_score'] - $tot_score_row['deduction']) . " (-" . $tot_score_row['deduction'] . ")</td>";
              echo "<td>" . $tot_score_row['rank'] . "</td>";
              echo "</tr>";
            }
          }

          // Display average and total rank
          if ($divz > 0) {
            $average = round(($totx_score - $totx_deduct) / $divz, 2);
          } else {
            $average = 0; // Handle division by zero
          }
          echo "<tr>";
          echo "<td></td>";
          echo "<td><b>Ave: $average</b></td>";
          echo "<td><b>Sum: $rank_score</b></td>";
          echo "</tr>";

          echo "</table>";
          echo "</td>";

          // Display placing
          echo "<td style='width: 17%!important;'><center><h3><span class='badge bg-primary'>" . ordinal($rspCtr) . "</span></h3><hr />$contXXname</center></td>";
          echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>"; // Close card-body
        echo "</div>"; // Close card
  
        // Insert rankings into the database after displaying the results
        foreach ($contestants as $contestant) {
          $contestant_id = $contestant['id'];
          $rank_score = $contestant['rank'];

          // Insert or update the rank in the rank_system table
          $rsChecker = $conn->query("SELECT * FROM rank_system WHERE subevent_id='$active_sub_event' AND contestant_id='$contestant_id'") or die($conn->error);
          if ($rsChecker->rowCount() > 0) {
            // Update existing rank
            $updateQuery = "UPDATE rank_system SET total_rank='$rank_score' WHERE subevent_id='$active_sub_event' AND contestant_id='$contestant_id'";
            $conn->query($updateQuery) or die("Update failed: " . $conn->error);
          } else {
            // Insert new rank
            $insertQuery = "INSERT INTO rank_system (subevent_id, contestant_id, total_rank) VALUES ('$active_sub_event', '$contestant_id', '$rank_score')";
            $conn->query($insertQuery) or die("Insert failed: " . $conn->error);
          }
        }
      }

      echo "</div>"; // Close card
      echo "</div>"; // Close container
    }
  }
  ?>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Le javascript
    ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
  <script src="assets/js/jquery.js"></script>
  <script src="assets/js/bootstrap-transition.js"></script>
  <script src="assets/js/bootstrap-alert.js"></script>
  <script src="assets/js/bootstrap-modal.js"></script>
  <script src="assets/js/bootstrap-dropdown.js"></script>
  <script src="assets/js/bootstrap-scrollspy.js"></script>
  <script src="assets/js/bootstrap-tab.js"></script>
  <script src="assets/js/bootstrap-tooltip.js"></script>
  <script src="assets/js/bootstrap-popover.js"></script>
  <script src="assets/js/bootstrap-button.js"></script>
  <script src="assets/js/bootstrap-collapse.js"></script>
  <script src="assets/js/bootstrap-carousel.js"></script>
  <script src="assets/js/bootstrap-typeahead.js"></script>
  <script src="assets/js/bootstrap-affix.js"></script>

  <script src="assets/js/holder/holder.js"></script>
  <script src="assets/js/google-code-prettify/prettify.js"></script>

  <script src="assets/js/application.js"></script>
</body>

</html>
