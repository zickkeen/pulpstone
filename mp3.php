<?php
include 'header.php';
ceklogin();
function getFiles($path = '../mnt') {
   
    // Open the path set
    if ($handle = opendir($path)){
       
        // Loop through each file in the directory
        while ( false !== ($file = readdir($handle)) ) {
           
            // Remove the . and .. directories
            if ( $file != "." && $file != ".." ) {
               
                // Check to see if the file is a directory
                if( is_dir($path . '/' . $file) ) {
                   
                    // The file is a directory, therefore run a dir check again
                    getFiles($path . '/' . $file);
                   
                }
               
                // Get the information about the file
                $fileInfo = pathinfo($file);
               
                // Set multiple extension types that are allowed
                $allowedExtensions = array('mp3', 'MP3');
               
                // Check to ensure the file is allowed before returning the results
                if( in_array($fileInfo['extension'], $allowedExtensions) ) {
                    echo '<li class="stop"><a href="' . $path . '/' . $file . '">' . $file . '</a></li>';
                }
               
            }
        }
       
        // Close the handle
        closedir($handle);
    }
}
css();
echo '
<script type="text/javascript">
var audio;
var playlist;
var tracks;
var current;
 
$(document).ready(function() {
 init();
});
function init(){
   current = 0;
   audio = $("audio");
   playlist = $(".playlist");
   tracks = playlist.find("li a");
   len = tracks.length - 1;
   audio[0].volume = .30;
   audio[0].play();
   playlist.find("a").click(function(e){
       e.preventDefault();
       link = $(this);
       current = link.parent().index();
       run(link, audio[0]);
   });
   audio[0].addEventListener("ended",function(e){
       current++;
       if(current == len){
           current = 0;
           link = playlist.find("a")[0];
       }else{
           link = playlist.find("a")[current];    
       }
       run($(link),audio[0]);
   });
}
function run(link, player){
       player.src = link.attr("href");
       par = link.parent();
       par.addClass("play").siblings().removeClass("stop");
       audio[0].load();
       audio[0].play();
}
</script>';

echo '

<div id="multimedia">
<audio id="audio" preload="auto" tabindex="0" controls mute type="audio/mpeg"></audio>

	<ol class="playlist scroll">';

		getFiles();

		echo '</ol>

	</div>

<small>File terintegrasi dengan samba</small>

</div>';

include 'footer.php';
?>
