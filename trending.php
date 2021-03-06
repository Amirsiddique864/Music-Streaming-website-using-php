<?php
session_start();
include('player.php');
$serverName = "localhost";
$username = "root";
$password = "";
$dbName = "music";
$conn = new mysqli($serverName,$username,$password,$dbName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" text="text/css" href="home.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <title>Home</title>
</head>
<script>
    function fun(x){
        document.getElementById(x).submit();
        
    }
</script>
<body>
    <div class="mainContainer" style="background: linear-gradient(to bottom, #000000 -10%, #f11767 100%);">
    <ul id="navbar" class="nav justify-content-center">
            <li class="nav-item">
            <a class="nav-link" href="home.php">HOME</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="discover.php">DISCOVER</a>
            </li>
            <li class="nav-item active">
            <a class="nav-link" href="trending.php">TRENDING</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="userplaylist.php">MY PLAYLIST</a>
            </li>
            <li class='nav-item profile'>
            <div class='dropdown'>
      <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><?php echo $_SESSION['user_name'][0]; ?></button>
      <ul class="dropdown-menu">
    <li><a href="userplaylist.php" style='color:black; font-size:13px; padding-left:10px;'>My Playlist</a></li>
    <li><a href="landing.php" style='color:black; font-size:13px; padding-left:10px;'>Log out</a></li>
  </ul>
    </div>
            </li>
        </ul>
        <div class="container-fluid">
            <?php
                $queryArtist = 'select collection_title.collection_name, artist.p_name, artist.Artist_name, artist.Artist_image from 
                collection_title inner join collection on collection.collection_title_id = collection_title.collection_title_id 
                inner join artist on collection.artist_id = artist.Artist_id WHERE collection_title.collection_title_id = 4';
                $resultArtist = $conn->query($queryArtist);
                $artist = array();
                while($f1 = $resultArtist->fetch_assoc()){$artist [] = $f1;}
                echo "<h2>".$artist[0]['collection_name']."</h2>";
                echo "<div class='row'>";
                foreach($artist as $r){
                    $temp = $r['p_name'];
                echo "<form action='play.php' method='get' id=$temp>";
                echo "<div class='col-lg-2' >";
                echo "<div class='card' onclick=fun("."'".$temp."'".")>";
                echo "<img class='card-img-top' src=".$r['Artist_image']." alt='Card image'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>".$r['Artist_name']."</h5>";
                echo "<input style='display:none' type=text name='artist' value=$temp>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</form>";
                }
                echo "</div>";
            ?>
            
        
            <?php 
            $querySing = 'select collection_title.collection_name, songs.song_name, songs.song_image, songs.song_address, artist.Artist_name 
            from collection_title inner join 
            collection on collection.collection_title_id = collection_title.collection_title_id 
            INNER JOIN songs on collection.song_id = songs.song_id 
            inner join artist on collection.artist_id = artist.Artist_id WHERE collection_title.collection_title_id=8';

            $singResult = $conn->query($querySing);
            $sing = array();
            $sindex = 0;
            while($sr = $singResult->fetch_assoc()){
                $sing []=$sr;
                $songsArray [] = $sr['song_address'];
                $songsNameArray [] = $sr['song_name'];
                $songsImageArray [] = $sr['song_image'];
                $songsArtistName [] = $sr['Artist_name'];
                
            }
            echo "<h2>".$sing[0]['collection_name']."</h2>";
            echo "<div class='row'>";
                foreach($sing as $s){
                echo "<div class='col-lg-2'>";
                    echo "<div class='card' id='sing'>";
                        echo "<img class='card-img-top' src=".$s['song_image']." alt='Card image'>";
                        echo "<div class='card-img-overlay'>";
                            echo "<button class='splay' onclick= passIndex($sindex) ><i class='fa' ".">&#xf04b;</i>
                            <button class='spause' style='display:none'><i  onclick='pau()'  class='fa'>&#xf04c;</i>";
                        echo "</div>";
                        echo "<div class='card-body'>";
                            echo "<h6>".$s['song_name']."</h6>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
                $sindex = $sindex+1;
                }
            echo "</div>";
        ?>
        
        </div>   
    </div>
            
    <script>
    function autoNext() {
  setInterval(function(){ 
      var u = document.getElementById('player');
      if(u.currentTime == u.duration){
          forw();
      } 
    }, 3000);
}
var prevIndex = 0;
function stateManage(){

}
var currentIndex;
function passIndex(x){
    $('.splay').css('display','block');
    $('.spause').css('display','none');
    currentIndex = x;
    var a = <?php echo json_encode($songsArray); ?>;
    var b = <?php echo json_encode($songsImageArray); ?>;
    var c = <?php echo json_encode($songsNameArray); ?>;
    var d = <?php echo json_encode($songsArtistName); ?>;
    console.log(d);
    pla(x,d[x],b[x],c[x]);
}

   
   function pla(x,y,z,w){
    autoNext();
        currentIndex = x;
        var passedArray =  <?php echo json_encode($songsArray); ?>;
        currentContent = <?php echo json_encode($songsNameArray); ?>;
        // console.log(currentContent);
        var g = document.getElementById('player');
        g.setAttribute('src',passedArray[x]);
        document.getElementById('pl').click();
        document.getElementById('player_title').innerHTML = w;
        document.getElementById('player_content').innerHTML = y;
        document.getElementById('player_image').src = z;
        document.getElementById('player_image').style.display = "block";
        document.getElementsByClassName('splay')[x].style.display = "none";
        document.getElementsByClassName('spause')[x].style.display = "block";
       
       
   }
   
   function pau(){
       document.getElementById('pa').click();
       document.getElementsByClassName('splay')[currentIndex].style.display = "block";
       document.getElementsByClassName('spause')[currentIndex].style.display = "none";
   }
   function forw(){
       var temp = <?php echo json_encode($sindex)?>;
       if(currentIndex==temp-1){
           passIndex(0);
       }
       else{
        passIndex(currentIndex+1);
       }
   }
   function back(){
       if(currentIndex==0){
           passIndex(0);
       }
       else{
           passIndex(currentIndex-1);
       }
   }
   </script>

</body>
</html>