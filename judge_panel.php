<!DOCTYPE html>
<html lang="en">

<?php
error_reporting(0);
include('header2.php');
include('session.php');
$judge_ctr = $_GET['judge_ctr'];
$subevent_id = $_GET['subevent_id'];
$getContestant_id = $_GET['contestant_id'];
$pageStat = $_GET['pStat'];

?>

<?php $event_query = $conn->query("select * from sub_event where subevent_id='$subevent_id'") or die(mysql_error());
while ($event_row = $event_query->fetch()) { ?>

    <?php
    $se_MEidxx = $event_row['mainevent_id'];
    $se_namexx = $event_row['event_name'];
    $se_statusxx = $event_row['status'];
    ?>

<?php } ?>
<?php

if ($se_statusxx == "activated") {

    $judge_query = $conn->query("select * from judges where subevent_id='$subevent_id' and judge_ctr='$judge_ctr'") or die(mysql_error());

    $num_row = $judge_query->rowcount();
    if ($num_row > 0) {

        while ($judge_row = $judge_query->fetch()) {
            $j_id = $judge_row['judge_id'];
            $j_name = $judge_row['fullname'];
            $j_code = $judge_row['code'];
            $jtype = $judge_row['jtype'];
            ?>

        <?php }
    }
} ?>

<body>

    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="brand" href="#"><img src="uploads/<?php echo $company_logo; ?>" width="23" height="23" />&nbsp; <font size="3">Pageant Tabulation System</font></a>
                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li>
                            <a href="selection.php">&laquo; Back to <i><strong>User Selection Panel</strong></i></a>
                        </li>
                        <li>
                            <a href="#">
                                <font color="white">Event: <strong><?php echo $se_namexx; ?></strong></font>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <font color="white">Judge: <strong><?php echo $j_name; ?>&nbsp;&nbsp;&nbsp;<?php echo $jtype; ?> </strong></font>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Subhead -->
    <?php
    if ($se_statusxx == "activated") {
        $judge_query = $conn->query("select * from judges where subevent_id='$subevent_id' and judge_ctr='$judge_ctr'") or die(mysql_error());
        $num_row = $judge_query->rowcount();
        if ($num_row > 0) {
            while ($judge_row = $judge_query->fetch()) {
                $j_id = $judge_row['judge_id'];
                $j_name = $judge_row['fullname'];
                $j_code = $judge_row['code'];
                ?>

                <table style="background-color: #30475E; width: 100% !important; height: 150px; text-indent: 25px;" align="center" border="0">
                    <tr>
                        <td>
                            <h1 style="color: whitesmoke !important;">Judge's Panel</h1>
                            <h4 style="color: whitesmoke !important;">Pageant Tabulation System</h4>
                        </td>
                    </tr>
                </table>

                <br />

            <?php }
        }
    } else {
        $j_name = "Event is still inactive. Please contact the Event Organizer."; ?>

        <table style="background-color: #30475E; width: 100% !important; height: 150px; text-indent: 25px;" align="center" border="0">
            <tr>
                <td>
                    <h1 style="color: whitesmoke !important;">Judge's Panel - <font color="red"><?php echo $j_name; ?></font></h1>
                    <h4 style="color: whitesmoke !important;">Pageant Tabulation System</h4>
                </td>
            </tr>
        </table>

    <?php } ?>

    <div class="container">
        <div class="row">
            <div class="span12">

                <?php if ($num_row > 0) { ?>

                    <ul class="nav nav-tabs alert-success">
                        <?php
                        if ($pageStat == "Change") {
                            $cont_query = $conn->query("select * from contestants JOIN dapartment On contestants.department_id = dapartment.department_id where subevent_id='$subevent_id' AND contestant_id='$getContestant_id'") or die(mysql_error());
                            while ($cont_row = $cont_query->fetch()) { ?>
                                <li><a><strong>Change Score Panel - <?php echo $cont_row['fullname']; ?></strong></a></li>
                            <?php }
                        } else {
                            $cont_query = $conn->query("select * from contestants JOIN dapartment On contestants.department_id = dapartment.department_id where subevent_id='$subevent_id' order by contestant_ctr") or die(mysql_error());
                            while ($cont_row = $cont_query->fetch()) {
                                $con_idTab = $cont_row['contestant_id']; ?>
                                <?php if ($getContestant_id == $con_idTab) { ?>
                                    <li class="active"><a href="judge_panel.php?judge_ctr=<?php echo $judge_ctr; ?>&subevent_id=<?php echo $subevent_id; ?>&contestant_id=<?php echo $con_idTab; ?>"><strong><?php echo $cont_row['contestant_ctr'] . ' - ' . $cont_row['category'] . ' - ' . $cont_row['fullname'] . '-' . $cont_row['department']; ?></strong></a></li>
                                <?php } else { ?>
                                    <li class=""><a href="judge_panel.php?judge_ctr=<?php echo $judge_ctr; ?>&subevent_id=<?php echo $subevent_id; ?>&contestant_id=<?php echo $con_idTab; ?>"><?php echo $cont_row['contestant_ctr'] . ' - ' . $cont_row['category'] . ' - ' . $cont_row['fullname'] . '-' . $cont_row['department']; ?></a></li>
                                <?php }
                            } ?>
                            <?php if ($getContestant_id == "allTally") { ?>
                                <li class="active"><a href="judge_panel.php?judge_ctr=<?php echo $judge_ctr; ?>&subevent_id=<?php echo $subevent_id; ?>&contestant_id=allTally"><strong>View Tally</strong></a></li>
                                <li><a href="selection.php"><strong><font color="red">Exit</font></strong></a></li>
                            <?php } else { ?>
                                <li class=""><a href="judge_panel.php?judge_ctr=<?php echo $judge_ctr; ?>&subevent_id=<?php echo $subevent_id; ?>&contestant_id=allTally">View Tally</a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>

                    <?php
                    if ($getContestant_id == "allTally") { ?>

                        <!-- Separate Rankings for Mr and Ms Categories -->
                        <?php
                        $categories = ['Mr', 'Ms'];
                        foreach ($categories as $category) {
                            echo "<h3>Ranking for $category</h3>";
                            $rankCtr = 0;

                            $score_queryzz = $conn->query("SELECT DISTINCT sr.contestant_id 
                                                          FROM sub_results sr 
                                                          JOIN contestants c ON sr.contestant_id = c.contestant_id 
                                                          WHERE sr.subevent_id='$subevent_id' 
                                                          AND sr.judge_id='$j_id' 
                                                          AND c.category='$category' 
                                                          ORDER BY sr.total_score DESC") or die(mysql_error());

                            while ($cont_row = $score_queryzz->fetch()) {
                                $rankCtr = $rankCtr + 1;
                                $conID = $cont_row['contestant_id'];

                                $score_query = $conn->query("select * from sub_results where contestant_id='$conID' AND judge_id='$j_id'") or die(mysql_error());
                                while ($score_row = $score_query->fetch()) {
                                    $myScore = $score_row['total_score'];
                                    $comments = $score_row['comments'];
                                }

                                $contzx_query = $conn->query("select * from contestants where contestant_id='$conID'");
                                $contzx_row = $contzx_query->fetch();
                                ?>

                                <table align="center" class="table table-bordered">
                                    <tr>
                                        <td align="center">
                                            <div style="text-align: center;">
                                                <img src="uploads/contestants/<?php echo $contzx_row['image']; ?>" width="200" height="200" style="border-radius: 50%; margin-bottom: 10px;">
                                                <br>
                                                <strong style="font-size: 18px;"><?php echo $contzx_row['fullname']; ?></strong>
                                                <br>
                                                <strong style="font-size: 16px;">Total Score Earned: <?php echo $myScore; ?>%</strong>
                                            </div>
                                        </td>
                                        <td align="center">
                                            <table align="center" class="table table-bordered">
                                                <tr>
                                                    <?php
                                                    $criteria_query = $conn->query("select * from criteria where subevent_id='$subevent_id' order by criteria_ctr ASC") or die(mysql_error());
                                                    while ($crit_row = $criteria_query->fetch()) { ?>
                                                        <td><center><?php echo $crit_row['criteria'] . " - " . $crit_row['percentage'] . "%"; ?></center></td>
                                                    <?php } ?>
                                                </tr>
                                                <tr>
                                                    <?php
                                                    $criteria_query = $conn->query("select * from criteria where subevent_id='$subevent_id' order by criteria_ctr ASC") or die(mysql_error());
                                                    while ($crit_row = $criteria_query->fetch()) { ?>
                                                        <td align="center" bgcolor="#C5EAF9">
                                                            <?php
                                                            if ($crit_row['criteria_ctr'] == 1) echo $score_row['criteria_ctr1'];
                                                            if ($crit_row['criteria_ctr'] == 2) echo $score_row['criteria_ctr2'];
                                                            if ($crit_row['criteria_ctr'] == 3) echo $score_row['criteria_ctr3'];
                                                            if ($crit_row['criteria_ctr'] == 4) echo $score_row['criteria_ctr4'];
                                                            if ($crit_row['criteria_ctr'] == 5) echo $score_row['criteria_ctr5'];
                                                            if ($crit_row['criteria_ctr'] == 6) echo $score_row['criteria_ctr6'];
                                                            if ($crit_row['criteria_ctr'] == 7) echo $score_row['criteria_ctr7'];
                                                            if ($crit_row['criteria_ctr'] == 8) echo $score_row['criteria_ctr8'];
                                                            if ($crit_row['criteria_ctr'] == 9) echo $score_row['criteria_ctr9'];
                                                            if ($crit_row['criteria_ctr'] == 10) echo $score_row['criteria_ctr10'];
                                                            ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            </table>
                                            <font size="2"><strong>Comment:</strong> <?php echo $comments; ?></font>
                                        </td>
                                        <td bgcolor="green"><center><font size="6" color="white"><?php echo $rankCtr; ?></font></center></td>
                                        <td width="10">
                                            <a title="Change <?php echo $contzx_row['fullname']; ?> scores" href="judge_panel.php?judge_ctr=<?php echo $judge_ctr; ?>&subevent_id=<?php echo $subevent_id; ?>&contestant_id=<?php echo $conID; ?>&pStat=Change" class="btn btn-success"><i class="icon-pencil"></i></a>
                                        </td>
                                    </tr>
                                </table>

                            <?php } ?>
                        <?php } ?>

                    <?php } else {
                        // Individual Contestant Scoring
                        $cont_query = $conn->query("select * from contestants where subevent_id='$subevent_id' AND contestant_id='$getContestant_id'") or die(mysql_error());
                        while ($cont_row = $cont_query->fetch()) { ?>

                            <div style="text-align: center;">
                                <h3><?php echo $se_namexx; ?> - <?php echo $cont_row['fullname']; ?></h3>
                                <img src="uploads/contestants/<?php echo $cont_row['image']; ?>" width="200" height="200" style="border-radius: 50%; margin-bottom: 10px;">
                                <br>
                                <strong style="font-size: 18px;">Total Score Earned: 
                                    <?php
                                    $score_query = $conn->query("select * from sub_results where subevent_id='$subevent_id' and judge_id='$j_id' and contestant_id='$getContestant_id'") or die(mysql_error());
                                    while ($score_row = $score_query->fetch()) {
                                        echo $score_row['total_score'] . "%";
                                    } ?>
                                </strong> of 100%
                            </div>

                            <br /><br />

                            <?php
                            $jstat_rowx = 0;
                            $jstat_query = $conn->query("select * from sub_results where subevent_id='$subevent_id' and judge_id='$j_id' and contestant_id='$getContestant_id'") or die(mysql_error());
                            while ($jstat_row = $jstat_query->fetch()) {
                                $jstat_rowx = 1;
                            }

                            if ($jstat_rowx == 1) { ?>

                                <!-- Edit Scores Form -->
                                <form method="POST" action="edit_submit_judging.php">
                                    <input type="hidden" value="<?php echo $cont_row['fullname']; ?>" name="contestant_name" />
                                    <input type="hidden" value="<?php echo $getContestant_id; ?>" name="contestant_id" />
                                    <input type="hidden" value="<?php echo $j_id; ?>" name="judge_id" />
                                    <input type="hidden" value="<?php echo $judge_ctr; ?>" name="judge_ctr" />
                                    <input type="hidden" value="<?php echo $se_MEidxx; ?>" name="mainevent_id" />
                                    <input type="hidden" value="<?php echo $subevent_id; ?>" name="subevent_id" />

                                    <table align="center" class="table table-bordered">
                                        <tr>
                                            <?php
                                            $criteria_query = $conn->query("select * from criteria where subevent_id='$subevent_id' order by criteria_ctr ASC") or die(mysql_error());
                                            while ($crit_row = $criteria_query->fetch()) { ?>
                                                <td width="10">
                                                    <center>
                                                        <font size="2">
                                                            <?php echo $crit_row['criteria'] . " - <b>" . $crit_row['percentage'] . "%</b>"; ?>
                                                        </font>
                                                    </center>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <?php
                                            $criteria_query = $conn->query("select * from criteria where subevent_id='$subevent_id' order by criteria_ctr ASC") or die(mysql_error());
                                            while ($crit_row = $criteria_query->fetch()) { ?>
                                                <td width="10">
                                                    <select class="form-control" style="width: 100%;" name="cp<?php echo $crit_row['criteria_ctr']; ?>">
                                                        <?php
                                                        $score_query = $conn->query("select * from sub_results where contestant_id='$getContestant_id' AND judge_id='$j_id'") or die(mysql_error());
                                                        $score_row = $score_query->fetch();
                                                        $selected_score = $score_row['criteria_ctr' . $crit_row['criteria_ctr']];

                                                        $n1 = -0.5;
                                                        while ($n1 < $crit_row['percentage']) {
                                                            $n1 = $n1 + 0.5; ?>
                                                            <option <?php if ($n1 == $selected_score) echo 'selected'; ?>><?php echo $n1; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </table>

                                    <strong>COMMENTS:</strong><br />
                                    <textarea name="jcomment" class="form-control" style="width: 99%;" placeholder="Enter comments here..."><?php echo $score_row['comments']; ?></textarea>

                                    <div class="modal-footer">
                                        <a title="click to cancel, changes made will never be save." href="judge_panel.php?judge_ctr=<?php echo $judge_ctr; ?>&subevent_id=<?php echo $subevent_id; ?>&contestant_id=<?php echo $getContestant_id; ?>&pStat=xChange" class="btn btn-default"><i class="icon-remove"></i> <strong>CANCEL</strong></a>
                                        <button title="Click to update scores." type="submit" class="btn btn-success"><i class="icon-ok"></i> <strong>UPDATE</strong></button>
                                    </div>
                                </form>

                            <?php } else { ?>

                                <!-- Submit Scores Form -->
                                <form method="POST" action="submit_judging.php">
                                    <input type="hidden" value="<?php echo $cont_row['fullname']; ?>" name="contestant_name" />
                                    <input type="hidden" value="<?php echo $getContestant_id; ?>" name="contestant_id" />
                                    <input type="hidden" value="<?php echo $j_id; ?>" name="judge_id" />
                                    <input type="hidden" value="<?php echo $judge_ctr; ?>" name="judge_ctr" />
                                    <input type="hidden" value="<?php echo $se_MEidxx; ?>" name="mainevent_id" />
                                    <input type="hidden" value="<?php echo $subevent_id; ?>" name="subevent_id" />

                                    <table align="center" class="table table-bordered">
                                        <tr>
                                            <?php
                                            $criteria_query = $conn->query("select * from criteria where subevent_id='$subevent_id' order by criteria_ctr ASC") or die(mysql_error());
                                            while ($crit_row = $criteria_query->fetch()) { ?>
                                                <td width="10">
                                                    <center>
                                                        <font size="2">
                                                            <?php echo $crit_row['criteria'] . " - <b>" . $crit_row['percentage'] . "%</b>"; ?>
                                                        </font>
                                                    </center>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <?php
                                            $criteria_query = $conn->query("select * from criteria where subevent_id='$subevent_id' order by criteria_ctr ASC") or die(mysql_error());
                                            while ($crit_row = $criteria_query->fetch()) { ?>
                                                <td width="10">
                                                    <select class="form-control" style="width: 100%;" name="cp<?php echo $crit_row['criteria_ctr']; ?>">
                                                        <?php
                                                        $n1 = -0.5;
                                                        while ($n1 < $crit_row['percentage']) {
                                                            $n1 = $n1 + 0.5; ?>
                                                            <option><?php echo $n1; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </table>

                                    <strong>COMMENTS:</strong><br />
                                    <textarea name="jcomment" class="form-control" style="width: 99%;" placeholder="Enter comments here..."></textarea>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"><i class="icon-ok"></i> <strong>SUBMIT</strong></button>
                                    </div>
                                </form>

                            <?php } ?>
                        <?php } ?>
                    <?php } ?>

                <?php } ?>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <?php include('footer.php'); ?>
    </div>

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
