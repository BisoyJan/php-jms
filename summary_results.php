<!DOCTYPE html>
<html lang="en">

<?php
include('header.php');
include('session.php');

// Debugging: Check database connection
if (!$conn) {
  die("Database connection failed: " . mysqli_connect_error());
}

// Check if main_event_id is passed in the URL
if (!isset($_GET['main_event_id'])) {
  die("main_event_id is not set in the URL.");
}

$active_main_event = $_GET['main_event_id'];

// Fetch main event details
function fetchMainEvent($conn, $main_event_id)
{
  $stmt = $conn->prepare("SELECT * FROM main_event WHERE mainevent_id = ?");
  $stmt->execute([$main_event_id]);
  return $stmt->fetch();
}

$main_event = fetchMainEvent($conn, $active_main_event);
if (!$main_event) {
  die("No data found for main_event_id: $active_main_event");
}

// Fetch sub-events for the main event
function fetchSubEvents($conn, $main_event_id)
{
  $stmt = $conn->prepare("SELECT * FROM sub_event WHERE mainevent_id = ?");
  $stmt->execute([$main_event_id]);
  return $stmt->fetchAll();
}

$sub_events = fetchSubEvents($conn, $active_main_event);
if (empty($sub_events)) {
  die("No sub-events found for main_event_id: $active_main_event");
}

// Fetch contestants for a sub-event and category
function fetchContestants($conn, $sub_event_id, $category)
{
  $stmt = $conn->prepare("SELECT DISTINCT c.contestant_id, c.fullname, c.department_id 
                          FROM contestants c
                          WHERE c.subevent_id = ? AND c.category = ?");
  $stmt->execute([$sub_event_id, $category]);
  return $stmt->fetchAll();
}

// Fetch criteria for a sub-event
function fetchCriteria($conn, $sub_event_id)
{
  $stmt = $conn->prepare("SELECT * FROM criteria WHERE subevent_id = ? ORDER BY criteria_ctr ASC");
  $stmt->execute([$sub_event_id]);
  return $stmt->fetchAll();
}

// Fetch scores for a contestant in a sub-event
function fetchScores($conn, $contestant_id, $sub_event_id)
{
  $stmt = $conn->prepare("SELECT * FROM sub_results WHERE contestant_id = ? AND subevent_id = ?");
  $stmt->execute([$contestant_id, $sub_event_id]);
  return $stmt->fetch();
}

// Fetch department name
function fetchDepartment($conn, $department_id)
{
  $stmt = $conn->prepare("SELECT department FROM dapartment WHERE department_id = ?");
  $stmt->execute([$department_id]);
  $result = $stmt->fetch();
  return $result ? $result['department'] : 'Unknown';
}

// Calculate ranks for contestants
function calculateRanks($contestants)
{
  usort($contestants, function ($a, $b) {
    return $b['total_score'] <=> $a['total_score'];
  });

  $rank = 1;
  $prev_score = null;
  foreach ($contestants as &$contestant) {
    if ($contestant['total_score'] !== $prev_score) {
      $rank = $rank; // Keep rank the same for ties
    }
    $contestant['rank'] = $rank;
    $prev_score = $contestant['total_score'];
    $rank++;
  }

  return $contestants;
}

// Render the tally sheet for a category
function renderTallySheet($conn, $sub_event, $category)
{
  $contestants = fetchContestants($conn, $sub_event['subevent_id'], $category);
  $criteria = fetchCriteria($conn, $sub_event['subevent_id']);

  if (empty($contestants)) {
    echo "<p>No contestants found for {$category}.</p>";
    return;
  }

  $contestant_scores = [];
  foreach ($contestants as $contestant) {
    $scores = fetchScores($conn, $contestant['contestant_id'], $sub_event['subevent_id']);
    $total_score = 0;

    foreach ($criteria as $criterion) {
      $criteria_ctr = "criteria_ctr" . $criterion['criteria_ctr'];
      $score = isset($scores[$criteria_ctr]) ? $scores[$criteria_ctr] : 0; // Ensure score is fetched correctly
      $total_score += $score;
    }

    $contestant_scores[] = [
      'fullname' => $contestant['fullname'],
      'department' => fetchDepartment($conn, $contestant['department_id']),
      'scores' => $scores,
      'total_score' => $total_score,
    ];
  }

  $ranked_contestants = calculateRanks($contestant_scores);

  echo "<h3>Tally Sheet for {$category}.</h3>";
  echo "<h4>EVENT: <strong>{$sub_event['event_name']}</strong></h4>";
  echo "<hr />";

  echo "<table align='center' class='table table-bordered' id='example'>
          <tr>
            <th>Rank</th>
            <th>Contestant</th>
            <th>Department</th>";

  foreach ($criteria as $criterion) {
    echo "<th>{$criterion['criteria']}</th>";
  }

  echo "<th>Total Score</th>
        </tr>";

  foreach ($ranked_contestants as $contestant) {
    echo "<tr>
            <td>{$contestant['rank']}</td>
            <td>{$contestant['fullname']}</td>
            <td>{$contestant['department']}</td>";

    foreach ($criteria as $criterion) {
      $criteria_ctr = "criteria_ctr" . $criterion['criteria_ctr'];
      echo "<td>" . (isset($contestant['scores'][$criteria_ctr]) ? $contestant['scores'][$criteria_ctr] : 0) . "</td>";
    }

    echo "<td><strong>{$contestant['total_score']}</strong></td>
          </tr>";
  }

  echo "</table>";
}
?>

<body>
  <div class="container">
    <div class="span12">
      <center>
        <h3><strong><?php echo $main_event['event_name']; ?></strong></h3>
        <h3>Tally Sheet</h3>
      </center>

      <section id="download-bootstrap">
        <div class="page-header">
          <table style="width: 100% !important;" align="center">
            <?php
            foreach ($sub_events as $sub_event) {
              echo "<tr><td>";

              // Render tally sheets for Mr. and Ms.
              renderTallySheet($conn, $sub_event, 'Mr');
              renderTallySheet($conn, $sub_event, 'Ms');

              echo "</td></tr>";
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
