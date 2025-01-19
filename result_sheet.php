<?php
include('header2.php');
include('session.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>MR and MS Placement</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }

    .container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background-color: #ffffff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    h1 {
      text-align: center;
      color: #333333;
      margin-bottom: 20px;
    }

    h2 {
      text-align: center;
      color: #555555;
      margin-top: 20px;
    }

    button.category-button {
      margin: 10px;
      padding: 12px 24px;
      font-size: 16px;
      font-weight: bold;
      color: #ffffff;
      background-color: #007bff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button.category-button:hover {
      background-color: #0056b3;
    }

    .placement-table {
      margin-top: 20px;
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    .placement-table th,
    .placement-table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }

    .placement-table th {
      background-color: #007bff;
      color: #ffffff;
      font-weight: bold;
    }

    .placement-table tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .placement-table tr:hover {
      background-color: #f1f1f1;
    }
  </style>
  <script>
    function showCategory(category) {
      document.getElementById('mr-results').style.display = category === 'MR' ? 'block' : 'none';
      document.getElementById('ms-results').style.display = category === 'MS' ? 'block' : 'none';
    }
  </script>
</head>

<body>
  <div class="container">
    <h1>Contestants Placement</h1>

    <!-- Buttons to toggle MR and MS placements -->
    <div style="text-align: center;">
      <button class="category-button" onclick="showCategory('MR')">Show MR Placements</button>
      <button class="category-button" onclick="showCategory('MS')">Show MS Placements</button>
    </div>

    <!-- MR Placement Table -->
    <div id="mr-results" style="display: none;">
      <h2>MR Placements</h2>
      <table class="placement-table">
        <tr>
          <th>Rank</th>
          <th>Full Name</th>
          <th>Department</th>
          <th>Total Score</th>
        </tr>
        <?php
        $mr_query = $conn->query("SELECT c.fullname, d.department, sr.total_score, sr.rank 
                                          FROM contestants c 
                                          JOIN sub_results sr ON c.contestant_id = sr.contestant_id
                                          JOIN dapartment d ON c.department_id = d.department_id
                                          WHERE c.category = 'MR'
                                          ORDER BY sr.rank ASC") or die($conn->error);
        while ($row = $mr_query->fetch()) {
          echo "<tr>"
            . "<td>{$row['rank']}</td>"
            . "<td>{$row['fullname']}</td>"
            . "<td>{$row['department']}</td>"
            . "<td>{$row['total_score']}</td>"
            . "</tr>";
        }
        ?>
      </table>
    </div>

    <!-- MS Placement Table -->
    <div id="ms-results" style="display: none;">
      <h2>MS Placements</h2>
      <table class="placement-table">
        <tr>
          <th>Rank</th>
          <th>Full Name</th>
          <th>Department</th>
          <th>Total Score</th>
        </tr>
        <?php
        $ms_query = $conn->query("SELECT c.fullname, d.department, sr.total_score, sr.rank 
                                          FROM contestants c 
                                          JOIN sub_results sr ON c.contestant_id = sr.contestant_id
                                          JOIN dapartment d ON c.department_id = d.department_id
                                          WHERE c.category = 'MS'
                                          ORDER BY sr.rank ASC") or die($conn->error);
        while ($row = $ms_query->fetch()) {
          echo "<tr>"
            . "<td>{$row['rank']}</td>"
            . "<td>{$row['fullname']}</td>"
            . "<td>{$row['department']}</td>"
            . "<td>{$row['total_score']}</td>"
            . "</tr>";
        }
        ?>
      </table>
    </div>
  </div>
</body>

</html>
