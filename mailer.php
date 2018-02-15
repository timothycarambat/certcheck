<?php
function makeMailBody($expiries){
  $res =  "<!DOCTYPE html>
<html>
<head>
<style>
body{
  font-family: 'Lato', 'Lucida Grande', 'Lucida Sans Unicode', Tahoma, Sans-Serif;
}
div.container {
    width: 100%;
    border: 1px solid #575fcf;
}

header, footer {
    padding: 1em;
    color: white;
    background-color: #575fcf;
    clear: left;
    text-align: center;
}
footer>a{
  color:#d2dae2;
}


article {
    padding: 1em;
    overflow: hidden;
}
@font-face {
    font-family: 'Lato';
    font-style: normal;
    font-weight: 400;
    src: local('Lato Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
  }

.expired{
  background-color: #f2dede;
  border-color: #ebcccc;
  color: #a94442;
}

.warning{
  background-color: #fcf8e3;
  border-color: #faf2cc;
  color: #8a6d3b;
}

.good{
  background-color: #dff0d8;
  border-color: #d0e9c6;
  color: #3c763d;
}

</style>
</head>
<body>

<div class='container'>

<header>
   <h1>SSL Checker</h1>
   <p style='font-size:10px;'><i>For the rest of us on shared hosting<i></p>
</header>
";

foreach ($expiries as $url => $days) {
  if(!is_numeric($days)){
    $res .= "<article class='expired'>
      <h1>".$url." &#9760;</h1>
      <p>This Domain's SSL Certificate is currently expired or unavailable. <a href='".$url."'>Check it out.</a></p>
    </article>
    ";
  }else{
    if( $days <= 15){
      $res .= "<article class='warning'>
        <h1>".$url." &#9888;</h1>
        <p>This Domain's SSL Certificate is going to expire in ".$days." ".($days > 1)? 'days':'day'.". Suggested You Renew this or be staged to do so.
      </article>
      ";
    }else{
      $res .= "<article class='good'>
        <h1>".$url." &#128077;</h1>
        <p>This Domain's SSL Certificate is going to expire in ".$days." days. You're good.
      </article>
      ";
    }
  }
}

$res .="
<footer>Help contribute to this tool at <a href='#'> Here </a></footer>
</div>
</body>
</html>
";

return $res;
}

 ?>
