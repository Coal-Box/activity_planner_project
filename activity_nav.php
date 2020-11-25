<?php
	
	$activityquery = "SELECT * FROM ActivityList";
    $activitystatement = $db->prepare($activityquery);

    $activitystatement->execute();

?>

<nav id="activities_nav">
	<ul id="nav_ul">
		<?php while ($activityrow = $activitystatement->fetch()): ?>
			<li><a href="activity.php?id=<?= $activityrow['ActivityListID'] ?>"><?= $activityrow['ActivityName'] ?></a></li>
		<?php endwhile ?>
</nav>