<h1 id="nav_banner">Destiny 2 Event Planner</h1>
<p>
	<?php if ($_SESSION['access'] === 1): ?>
		<a href="login.php">Login</a> <a href="Signup.php">Sign-up </a>
	<?php else : ?>
		<a href="Logout.php">Log-Out</a>
		<h5>Logged in as <?= $_SESSION['user'] ?></h5>
	<?php endif ?>
</p>
<nav id="nav_nav">
    <ul id="nav_ul">
        <li><a href="index.php">Home</a></li>
        <li><a href="newevent.php">New Event</a></li>
        <li><a href="scheduledactivites.php">Scheduled Activities</a></li>
        <li><a href="activitylist.php">Activity List</a></li>
        <li><a href="profile.php">Profile</a></li>
    </ul>
</nav>