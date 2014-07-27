<html>
<head>
  <link rel="stylesheet" href="style.css"/>
  <title>read me.</title>
</head>
<body>
  <div id="container">
    <div id="menu"> 
        <?php
            $menu = array(
            'HOME'=>'', 
            'SUBMISSION'=>'submit',
            "AUTHOR'S INDEX"=>'authind');
            foreach ($menu as $tab => $link) {
                echo '<div class="tab">';
                echo '<a href="' . $link . '">' . $tab . '</a></div>';
            }
        ?>
    </div>
    <div id="top">
    <div id="overlay">
      <img class="bg" alt="" src="city.jpg" />
    </div>
    <div id="about">    
      <h2> Presently </h2>
      <?php 
        echo date('l, F d, Y') . '<br />';
      ?>
    </div>
    </div>
    <div id="transition">
    </div>
    <br />
    <div id="posts-container" class="top">    
    <?php
      include("dbinfo.inc.php");
      include ("email.php");
      $mysqli = new mysqli($host, $username, $password, $database);
      if ($mysqli->connect_errno) {
        echo "Failed";
      }

      function get_author($mysqli, $id) {
        $author_info = array();
        $query = "SELECT name, email FROM authors WHERE ID=" . $id;
        if ($stmt = $mysqli->prepare($query)) {
          $stmt->execute();
          $stmt->store_result();
          $stmt->bind_result($n, $e);
          $stmt->fetch();
          $author_info['name'] = $n;
          $author_info['email'] = $e;
        }
        return $author_info;
      }
      
      if (!empty($_GET)) {
        if (isset($_GET['delete'])) {         
          $query = "DELETE FROM articles WHERE ID=" . $_GET['delete'];
          mysqli_query($mysqli, $query);
        }
      }
      function get_db($mysqli) {
        $query = "SELECT title, author_id, body, ID FROM articles ORDER BY ID DESC";
        if ($stmt = $mysqli->prepare($query)) {
          $stmt->execute();
          $stmt->store_result();
          $stmt->bind_result($a, $b, $c, $d);
          while ($stmt->fetch()) {
            if (strlen($c) > 500) {
                $c = substr($c,0,500) . ' ...';
            }
            $author_info = get_author($mysqli, $b);
            echo '<div class="whole"><article id="' .$d .'" class="entry"><span class="title">';
            echo '<h2><a href="article.php?id=' . $d . '">';
            echo $a . '</a></h2></span><span class="author">';
            echo check($author_info['email'], $author_info['name']);
            echo '</span><br /><div class="body">' . $c;
            echo '</div></article>'; 
            echo '<a class="del" href="/kc/?delete=' . $d . '"> delete <a/>';
            echo '</form></div>';
          }
          $stmt->free_result();
          $stmt->close();
       }
      }    
      get_db($mysqli);
    ?>
    </div>
  </div>
</body>
</html>
