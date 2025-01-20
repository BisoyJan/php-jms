<!DOCTYPE html>
<html lang="en">

<?php
include('header.php');
include('session.php');

// Debugging: Check database connection
if (!$conn) {
  die("Database connection failed: " . mysqli_connect_error());
} else {
  echo "<script>console.log('Database connected successfully!');</script>";
}

// Check if main_event_id is passed in the URL
if (!isset($_GET['main_event_id'])) {
  die("<script>console.error('main_event_id is not set in the URL.');</script>");
}

$active_main_event = $_GET['main_event_id'];
echo "<script>console.log('main_event_id:', " . json_encode($active_main_event) . ");</script>"; // Log main_event_id

// Fetch main event details
$stmt = $conn->prepare("SELECT * FROM main_event WHERE mainevent_id = ?");
$stmt->execute([$active_main_event]);
$event_query = $stmt->fetchAll();

if (empty($event_query)) {
  die("<script>console.error('No data found for main_event_id:', " . json_encode($active_main_event) . ");</script>");
} else {
  echo "<script>console.log('Main event data found:', " . json_encode($event_query) . ");</script>"; // Log main event data
}
?>

<body>
  <div class="container">
    <div class="span12">
      <?php
      foreach ($event_query as $event_row) {
        echo "<center>";
        include('doc_header.php');

        echo "<table>
                <tr>
                  <td align='center'>
                    <h3><strong>{$event_row['event_name']}</strong></h3>
                  </td>
                </tr>
                <tr>
                  <td align='center'>
                    <h3>Tally Sheet</h3>
                  </td>
                </tr>
              </table>
            </center>";
      }
      ?>

      <section id="download-bootstrap">
        <div class="page-header">
          <table style="width: 100% !important;" align="center">
            <?php
            // Fetch distinct school years (sy) for the organizer and main event
            $stmt = $conn->prepare("SELECT DISTINCT sy FROM main_event WHERE organizer_id = ? AND mainevent_id = ?");
            $stmt->execute([$session_id, $active_main_event]);
            $sy_query = $stmt->fetchAll();

            if (empty($sy_query)) {
              die("<script>console.error('No school years found for organizer_id:', " . json_encode($session_id) . ", 'and main_event_id:', " . json_encode($active_main_event) . ");</script>");
            } else {
              echo "<script>console.log('School years found:', " . json_encode($sy_query) . ");</script>"; // Log school years
            }

            foreach ($sy_query as $sy_row) {
              $sy = $sy_row['sy'];
              echo "<script>console.log('Processing school year:', " . json_encode($sy) . ");</script>"; // Log current school year
            
              // Fetch main events for the school year
              $stmt = $conn->prepare("SELECT * FROM main_event WHERE sy = ?");
              $stmt->execute([$sy]);
              $MEctrQuery = $stmt->fetchAll();

              if (empty($MEctrQuery)) {
                die("<script>console.error('No main events found for school year:', " . json_encode($sy) . ");</script>");
              } else {
                echo "<script>console.log('Main events found for school year:', " . json_encode($MEctrQuery) . ");</script>"; // Log main events
              }

              echo "<tr>
                        <td>";

              // Fetch sub-events for the main event
              $stmt = $conn->prepare("SELECT * FROM sub_event WHERE mainevent_id = ?");
              $stmt->execute([$active_main_event]);
              $SEctrQuery = $stmt->fetchAll();

              if (empty($SEctrQuery)) {
                die("<script>console.error('No sub-events found for main_event_id:', " . json_encode($active_main_event) . ");</script>");
              } else {
                echo "<script>console.log('Sub-events found:', " . json_encode($SEctrQuery) . ");</script>"; // Log sub-events
              }

              // Separate tally sheets for Mr. and Ms.
              $categories = ['Mr', 'Ms'];
              foreach ($categories as $category) {
                echo "<h3>Tally Sheet for {$category}.</h3>";

                foreach ($SEctrQuery as $SECtr) {
                  $rs_subevent_id = $SECtr['subevent_id'];
                  echo "<script>console.log('Processing sub-event:', " . json_encode($SECtr) . ");</script>"; // Log current sub-event
            
                  echo "<h4>EVENT: <strong>{$SECtr['event_name']}</strong></h4>
                              <hr />";

                  // Fetch contestants for the sub-event and category
                  $stmt = $conn->prepare("SELECT DISTINCT fullname, contestant_id FROM contestants WHERE subevent_id = ? AND category = ?");
                  $stmt->execute([$rs_subevent_id, $category]);
                  $contxx_query = $stmt->fetchAll();

                  if (empty($contxx_query)) {
                    echo "<script>console.warn('No contestants found for subevent_id:', " . json_encode($rs_subevent_id) . ", 'and category:', " . json_encode($category) . ");</script>"; // Log missing contestants
                  } else {
                    echo "<script>console.log('Contestants found:', " . json_encode($contxx_query) . ");</script>"; // Log contestants
                  }

                  // Fetch criteria for the sub-event
                  $stmt = $conn->prepare("SELECT * FROM criteria WHERE subevent_id = ? ORDER BY criteria_ctr ASC");
                  $stmt->execute([$rs_subevent_id]);
                  $criteria_query = $stmt->fetchAll();

                  if (empty($criteria_query)) {
                    echo "<script>console.warn('No criteria found for subevent_id:', " . json_encode($rs_subevent_id) . ");</script>"; // Log missing criteria
                  } else {
                    echo "<script>console.log('Criteria found:', " . json_encode($criteria_query) . ");</script>"; // Log criteria
                  }

                  // Fetch scores and calculate ranks
                  $contestant_scores = [];
                  foreach ($contxx_query as $contxx_row) {
                    $contxzID = $contxx_row['contestant_id'];

                    // Fetch criteria scores for the contestant
                    $stmt = $conn->prepare("SELECT * FROM sub_results WHERE contestant_id = ? AND subevent_id = ?");
                    $stmt->execute([$contxzID, $rs_subevent_id]);
                    $criteria_scores = $stmt->fetchAll();

                    if (!empty($criteria_scores)) {
                      $total_score = 0;
                      $scores = [];
                      foreach ($criteria_query as $criteria) {
                        $criteria_ctr = "criteria_ctr" . $criteria['criteria_ctr'];
                        $score = $criteria_scores[0][$criteria_ctr] ?? 0; // Default to 0 if score is missing
                        $scores[$criteria_ctr] = $score;
                        $total_score += $score;
                      }

                      $contestant_scores[] = [
                        'fullname' => $contxx_row['fullname'],
                        'scores' => $scores,
                        'total_score' => $total_score,
                      ];
                    }
                  }

                  // Sort contestants by total score in descending order
                  usort($contestant_scores, function ($a, $b) {
                    return $b['total_score'] <=> $a['total_score'];
                  });

                  // Assign ranks (handle ties)
                  $rank = 1;
                  $prev_score = null;
                  foreach ($contestant_scores as &$contestant) {
                    if ($contestant['total_score'] !== $prev_score) {
                      $rank = $rank + 0; // Increment rank only if the score changes
                    }
                    $contestant['rank'] = $rank;
                    $prev_score = $contestant['total_score'];
                    $rank++;
                  }

                  echo "<table align='center' class='table table-bordered' id='example'>
                                <tr>
                                  <th>Rank</th>
                                  <th>Contestant</th>";

                  // Dynamically generate criteria columns
                  foreach ($criteria_query as $criteria) {
                    echo "<th>{$criteria['criteria']}</th>";
                  }

                  echo "<th>Total Score</th>
                                </tr>";

                  // Display contestants with their rank and scores
                  foreach ($contestant_scores as $contestant) {
                    echo "<tr>
                                    <td>{$contestant['rank']}</td>
                                    <td>{$contestant['fullname']}</td>";

                    // Dynamically display scores for each criteria
                    foreach ($criteria_query as $criteria) {
                      $criteria_ctr = "criteria_ctr" . $criteria['criteria_ctr'];
                      echo "<td>{$contestant['scores'][$criteria_ctr]}</td>";
                    }

                    echo "<td><strong>{$contestant['total_score']}</strong></td>
                                  </tr>";
                  }

                  echo "</table>";
                }
              }

              echo "</td>
                      </tr>";
            }
            ?>
          </table>
        </div>
      </section>
    </div>
  </div>

  <?php include('footer.php'); ?>
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
