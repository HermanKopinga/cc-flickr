<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="nl">
        
<head>
  <title>Creative Commons attribution generator for Flickr images (beta)</title>
  <meta name="generator" content="Dusty old hand coding and a bit of Flickr-api"/>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

  <style type="text/css">
  a
  {
          text-decoration:	none;
          color:		black;
  }
  a.explicit
  {
          text-decoration:	underline;
          color:		gray;
  }
  a img
  {
          border:0;
  } 
  body
  {
          font-family: 		Monaco, "Lucida Console", monospace, sans-serif;
  }

  div.image
  {
          margin-left:		20%;
          margin-top:		10%;
  }

  div.main
  {
          margin-left:		5%;
          margin-top:		2%;
          text-align:		left;
          font-family:		sans-serif;
  }
  div.explanation
  {
          margin-left:		5%;
          margin-top:		2%;
          text-align:		left;
          font-family:		sans-serif;
          width:		600px;
  }

  </style>
</head>

<body>
<div class="page">
  <div class="image">
  <a href="http://www.flickr.com"><img src="flickr_logo.gif" alt="Flickr logo" /></a><br />
  <br />
  <a href="http://www.creativecommons.org"><img src="cc.logo.png" alt="Creative Commons logo" /></a><br />
  <br />
  Tool by: <a href="http://herman.kopinga.nl">Herman Kopinga</a> <a href="mailto: herman@kopinga.nl">(mail)</a>
  </div>
  <div class="main">
  <form action="" method="post">
  <div>
    Imageid or URL:<input type="text" size="40" name="image" <? if ($_POST['image']){echo "value=\"" . $_POST['image'] . "\"";} ?> />
    <input type="submit" value="CC" /><br />
  </div>
  </form>


<?
function flickr_connect()
{
  require_once("phpFlickr/phpFlickr.php");
  $f = new phpFlickr("c69cfe588168a6895e30ff4b54fc19ab");
  return $f;
}

//echo $_SERVER["HTTP_ACCEPT_LANGUAGE"];
//echo "<br />";



if ($_POST['image'])
{
  if (preg_match("/http:\/\/([a-z0-9]{2,}\-?\.[a-z]{2,3})(\.?[a-z]{2,3})?/i", $_POST['image'])) 
  {
    // nog wat doen om alleen ID te krijgen.
    if (ereg("\/([0-9]+)\/",$_POST['image'],$results))
    {
      $imageid = $results[1];
    }
    else
    {
      echo $_POST['image'] . ": invalid Flickr URL.<br />";    
    }
  }
  elseif(is_numeric($_POST['image']))
  {
    $imageid = $_POST['image'];
  }
  else
  {
    echo $_POST['image'] . ": invalid image ID.<br />";
  }

  $f = flickr_connect();
  $user = $f->urls_lookupUser($_POST['image']);
  $person = $f->people_getInfo($user['id']);
  $photo = $f->photos_getInfo($imageid);
  
  if ($photo['license'])
  {
    $licenses = $f->photos_licenses_getinfo();

    // Format 1, for dead-tree.
    echo "<b>Dead tree format:</b><br />\n";
    echo $photo['title'] . " by " . $photo['owner']['realname'] . "<br />\n";
    echo $photo['urls']['url'][0]['_content'] . "<br />\n";
    echo $licenses[$photo['license']]['name'] . "<br />\n";

    // Format 2, for web.
    echo "<br />\n";
    echo "<b>Web format:</b><br />\n";
    echo "<a href=\"" . $photo['urls']['url'][0]['_content'] . "\" class=\"explicit\">" . $photo['title'] . "</a> by " . $photo['owner']['realname'] . "<br />\n";
    echo "<a href=\"" . $licenses[$photo['license']]['url'] . "\" class=\"explicit\">" . $licenses[$photo['license']]['name'] . "</a><br />\n";
    
    // Format 2, for web (in HTML).
    echo "<br />\n";
    echo "<b>Web format (in HTML):</b><br />\n";
    echo "<pre>\n";
    echo "&lt;a href=\"" . $photo['urls']['url'][0]['_content'] . "\"&gt;" . $photo['title'] . "&lt;/a&gt; by " . $photo['owner']['realname'] . "&lt;br /&gt;\n";
    echo "&lt;a href=\"" . $licenses[$photo['license']]['url'] . "\"&gt;" . $licenses[$photo['license']]['name'] . "&lt;/a&gt;&lt;br /&gt;\n";
    echo "</pre>\n";
  }
  elseif ($photo['license'] === "0")
  {
    echo "All rights reserved! The Flickr user " . $photo['owner']['realname'] . " has chosen not to distribute this image using a Creative Commons license.<br />";
    echo "Please contact <a href=\"" . $photo['urls']['url'][0]['_content'] . "\">" . $photo['owner']['realname'] . "</a> to obtain rights to use this image.<br />";
  }
  else
  {
    echo "No info found.<br />";
  }
}
else if ($_GET['licenses'])
{
  $f = flickr_connect();
  $licenses = $f->photos_licenses_getinfo();

  echo "<br /><b>Possible Flickr foto licenties:</b><br />";
  foreach ($licenses as $license)
  {
    echo "<a href=\"" . $license['url'] . "\">" . $license['name'] . "</a><br />";
  }
}
else
{
?>
</div>
<div class="explanation">
Creative Commons attribution generator for Flickr images.<br />
<br />
Why? Because it takes about 20 clicks, a bit of waiting time and 3 times copy-paste to create a correct attribution using Flickr's interface. This is frustrating work when you have a complete presentation with 20+ images that need to be correctly annotated. Using this tool the attribution hassle is reduced so attribution and using content with a free(-er) licence is reduced.<br />
<br />
Finding Creative Common licenced work is easy using Flickr's <a href="http://www.flickr.com/search/advanced/" class="explicit">advanced search</a>.<br />
<br />
Paste the ID or full URL of a Flickr image and when a Creative Commons license is attached to the image the attribution will be generated. For example try: 3019886055.
</div>
<?
}
//  <a href="?licenses=show">all Flickr licenses</a><br />
?>
  </div>
</div>


<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
  print 'var pageTracker = _gat._getTracker("UA-5698495-1");';
  pageTracker._trackPageview();
</script>
    
</body>
</html>
