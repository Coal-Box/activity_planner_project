 <?php
     define('DB_DSN','mysql:host=localhost;dbname=destiny_event_planner;charset=utf8');
     define('DB_USER','eventuser');
     define('DB_PASS','server!8Pat');     
     
     try {
         $db = new PDO(DB_DSN, DB_USER, DB_PASS);
     } catch (PDOException $e) {
         print "Error: " . $e->getMessage();
         die();
     }
 ?>