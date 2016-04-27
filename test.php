<?php

?>
<script type="text/javascript">

    function vidplay() {
       var video1 = document.getElementById("Video1");
       var video2 = document.getElementById("Video2");
       var button = document.getElementById("play");
       if (video1.paused) {
          video1.play();
          video2.play();
          button.textContent = "||";
       } else {
          video1.pause();
          video2.pause();
          button.textContent = ">";
       }
    }

    function restart() {
        var video1 = document.getElementById("Video1");
        var video2 = document.getElementById("Video2");
        video1.currentTime = 0;
        video2.currentTime = 0;
    }

    function skip(value) {
        var video1 = document.getElementById("Video1");
        var video2 = document.getElementById("Video2");
        video1.currentTime += value;
        video2.currentTime += value;
    }      
</script>

</head>
<body>        

<video id="Video1" >
     <source src="../videos_e/201511301700240.mp4" type="video/mp4" width="300px" />
</video>
<video id="Video2" >
     <source src="../videos_e/201511301700240.mp4" type="video/mp4" width="300px" />
</video>

<div id="buttonbar">
    <button id="restart" onclick="restart();">[]</button> 
    <button id="rew" onclick="skip(-10)">&lt;&lt;</button>
    <button id="play" onclick="vidplay()">&gt;</button>
    <button id="fastFwd" onclick="skip(10)">&gt;&gt;</button>
</div>
