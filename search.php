<?php
  include("config.php");
  include("classes/SitesResultsProvider.php");
  include("classes/ImageResultsProvider.php");
  
  if(isset($_GET["term"])) {
    $term = $_GET["term"];
  } else {
    exit("You must enter a search term");
  }
  

  $type = isset($_GET["type"]) ? $_GET["type"] : $type = "sites";
  $page = isset($_GET["page"]) ? $_GET["page"] : $page = 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Welcome to Scoop</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>

<body>
  <div class="wrapper">
    <header>
      <div class="headerContent">
        <div class="logoContainer">
          <a href="index.php">
            <img src="assets/images/logo.png">
          </a>
        </div>

        <div class="searchContainer">
          <form action="search.php" method="GET">
            <div class="searchBarContainer">
              <input type="hidden" name="type" value="<?php echo $type; ?>">
              <input type="text" class="searchBox" name="term" value="<?php echo $term; ?>">
              <button class="searchButton">
                <img src="assets/images/icons/search.png" alt="Search">
              </button>
            </div>
          </form>
        </div>

      </div>

      <div class="tabsContainer">
        <ul class="tabList">
          <li class="<?php echo $type === 'sites' ? 'active' : '' ?>">
            <a href='<?php echo "search.php?term=$term&type=sites"; ?>'>
              Sites
            </a>
          </li>
          <li class="<?php echo $type === 'images' ? 'active' : '' ?>">
            <a href='<?php echo "search.php?term=$term&type=images"; ?>'>
              Images
            </a>
          </li>

        </ul>
      </div>
    </header>
    <section class="mainResults">
      <?php
        
        if($type == "sites") {
        $resultsProvider = new SitesResultsProvider($con);
        $pageSize = 20;
        } else {
          $resultsProvider = new ImageResultsProvider($con);
          $pageSize = 30;
        }
        $numResults =  $resultsProvider->getNumResults($term);

        echo "<p class='resultsCount'>$numResults results found</p>";
        if($numResults == 0) {
          echo "<h3>We Scooped and found nothing, maybe try a different search term</h3><div class='notFound'><img class='scoop' src='https://img.icons8.com/ios/50/000000/ice-cream-scoop.png'><img src='https://img.icons8.com/ios/50/000000/ice-cream-cone.png' class='iceCream'>";
        }
        echo $resultsProvider->getResultsHtml($page, $pageSize, $term);

        
        
      ?>
    </section>
    <div className="modalbg"></div>
    <footer>
      <div class="pageButtons">
        <div class="pageNumberContainer">
          <img src="assets/images/pageStart.png">
        </div>
        <?php
        
        $pagesToShow = 10;
        $numPages= ceil($numResults/$pageSize);
        $pagesLeft = min($pagesToShow, $numPages);

        $currentPage = $page - floor($pagesToShow/2);
        if($currentPage < 1) {
          $currentPage = 1;
        }
        if($currentPage + $pagesLeft > $numPages + 1) {
          $currentPage = $numPages + 1 - $pagesLeft;
        }
        
        while($pagesLeft != 0 && $currentPage <= $numPages) {

          if($currentPage == $page) {
            echo "<div class='pageNumberContainer'>
                <img src='assets/images/pageSelected.png'>
                <span class='pageNumber' >$currentPage</span>          
              </div>";
          } else {
            echo "<div class='pageNumberContainer'>
                <a href='search.php?term=$term&type=$type&page=$currentPage'>
                  <img src='assets/images/page.png'>
                  <span class='pageNumber' >$currentPage</span>
                </a>
              </div>";
          }
          
          if ($numPages == 1){
            echo "<div class='pageNumberContainer'>
                  <img src='assets/images/page.png'>
                </a>
              </div>";
          }


            $currentPage++;
            $pagesLeft--;
        }

        if ($numResults == 0) {
          echo "<div class='pageNumberContainer'>
                  <img src='assets/images/page.png'>
                </div>
                <div class='pageNumberContainer'>
                  <img src='assets/images/page.png'>
                </div>";
        }
        
        ?>
        <div class="pageNumberContainer">
          <img src="assets/images/pageEnd.png">
        </div>
      </div>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

  <script type="text/javascript" src="assets/js/script.js"></script>
</body>

</html>