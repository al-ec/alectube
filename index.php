<?php

// alec armbruster, open source
// github.com/al-ec/streamin.gg
// use this legally!

// configure libraries
use Alltube\Config;
use Alltube\VideoDownload;
require_once __DIR__.'/vendor/autoload.php'; // change me
$downloader = new VideoDownload(
new Config(
[
'youtubedl' => '/usr/lib/python3/dist-packages/youtube_dl/__main__.py', // change me
'python'    => '/usr/bin/python', // change me
]
)
);

// set "no video" to "on".
$noVideoMsg = 1;

// check to see if url is submitted and if it's valid...
if (isset($_POST['URL']) && strpos($_POST['URL'], 'http') === 0) {
$videoURL = $_POST['URL'];
// start collecting different url's from source
$videoandaudio = array_shift($downloader->getURL($videoURL,'best'));
$video = array_shift($downloader->getURL($videoURL,'bestvideo+bestaudio'));
$audio = array_pop($downloader->getURL($videoURL,'bestvideo+bestaudio'));
// have server backend generate mp3 from buffer
$cmdGenerateMP3 = "sh ./mp3.sh " . $_POST['URL'];
$mp3 = exec($cmdGenerateMP3);
// check if url's are empty for any reason, return error...
if (empty($videoandaudio)|| empty($video) || empty($audio)) {
$noVideoMsg == 1;
} else {
$noVideoMsg = 0;
}
}

?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>streamin.gg </title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">
        <script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js">
        </script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            function submitForm() {
                document.getElementById("submitButton").setAttribute("class", "button is-medium is-loading");
            }
        </script>
    </head>

    <body>
        <section class="section">
            <div class="container">
                <h1 class="title"><a href="https://streamin.gg" style="color: black;">streamin.gg</a>
                </h1>
                <p class="subtitle">generate mp4 and mp3 files from video links!</p>
                <div class="field has-addons">
                    <div class="control is-expanded has-icons-left">
                        <form action="index.php" id="theform" method="post" onsubmit="submitForm()">
                            <input class="input is-medium" type="text" name="URL" id="URL" placeholder="
<?php
if ($noVideoMsg == 1) {
echo " Paste video link from browser ";
} else {
echo "Links for " . $_POST['URL'];
}
?>
                                                                                                        
                            "> <span class="icon is-left">
                <i class="fas fa-video">
                </i>
              </span> </div>
                    <div class="control">
                        <button type="submit" id="submitButton" class="button is-primary is-medium">Download</button>
                    </div>
                    </form>
                </div>

<?php if($noVideoMsg == "1") {
echo "<br><article class='message has-icons-right is-small is-alert'><div class='message-header'><span class='icon is-right'>
<i class='fas fa-warning'></i>
</span> <p>No video link detected</p></div><div class='message-body'>We can't bake without any ingredients! :(</div></article>";
} else {
echo "<br><article class='message is-success'><div class='message-header'><p>Fresh out of the video oven!</p></div><div class='message-body '><div class='columns'>";
echo "<div class='column is-half'><a href='";
echo $videoandaudio;
echo "' class='button is-link'>Highest Quality<span class='tag' style='margin-left: 5px;'>mp4</span></a></div>";
echo "<div class='column is-quarter'><a href='";
echo $video;
echo "' class='button'>Video Only<span class='tag' style='margin-left: 5px;'>mp4</span></a></div>";
echo "<div class='column is-quarter'><a href='";
echo "https://streamin.gg/dl/".$mp3.".mp3";
echo "' class='button is-info'>Audio Only<span class='tag' style='margin-left: 5px;'>mp3</span></a></div>";
echo "</div></div>";
echo "</article>"; 
} ?>
            </div>
        </section>
    </body>

    </html>
