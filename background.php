<html>
<head>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<style type="text/css">
#container {
  position:relative;
  height:610px;
  width:610px;
}
#container img {
  position:absolute;
  left:0;
}
@-webkit-keyframes imgFade {
 0% {
   opacity:1;
 }
 17% {
   opacity:1;
 }
 25% {
   opacity:0;
 }
 92% {
   opacity:0;
 }
 100% {
   opacity:1;
 }
}

@-moz-keyframes imgFade {
 0% {
   opacity:1;
 }
 17% {
   opacity:1;
 }
 25% {
   opacity:0;
 }
 92% {
   opacity:0;
 }
 100% {
   opacity:1;
 }
}

@-o-keyframes imgFade {
 0% {
   opacity:1;
 }
 17% {
   opacity:1;
 }
 25% {
   opacity:0;
 }
 92% {
   opacity:0;
 }
 100% {
   opacity:1;
 }
}

@keyframes imgFade {
 0% {
   opacity:1;
 }
 17% {
   opacity:1;
 }
 25% {
   opacity:0;
 }
 92% {
   opacity:0;
 }
 100% {
   opacity:1;
 }
}

#container img {
  -webkit-animation-name: imgFade;
  -webkit-animation-timing-function: ease-in-out;
  -webkit-animation-iteration-count: infinite;
  -webkit-animation-duration: 8s;

  -moz-animation-name: imgFade;
  -moz-animation-timing-function: ease-in-out;
  -moz-animation-iteration-count: infinite;
  -moz-animation-duration: 8s;

  -o-animation-name: imgFade;
  -o-animation-timing-function: ease-in-out;
  -o-animation-iteration-count: infinite;
  -o-animation-duration: 8s;

  animation-name: imgFade;
  animation-timing-function: ease-in-out;
  animation-iteration-count: infinite;
  animation-duration: 8s;
}

#container img:nth-of-type(1) {
  -webkit-animation-delay: 6s;
  -moz-animation-delay: 6s;
  -o-animation-delay: 6s;
  animation-delay: 6s;
}
#container img:nth-of-type(2) {
  -webkit-animation-delay: 4s;
  -moz-animation-delay: 4s;
  -o-animation-delay: 4s;
  animation-delay: 4s;
}
#container img:nth-of-type(3) {
  -webkit-animation-delay: 2s;
  -moz-animation-delay: 2s;
  -o-animation-delay: 2s;
  animation-delay: 2s;
}
#container img:nth-of-type(4) {
  -webkit-animation-delay: 0;
  -moz-animation-delay: 0;
  -o-animation-delay: 0;
  animation-delay: 0;
}
</style>
</head>
<body>
<div id="container">
    <img src="images/zero.jpg">
    <img src="images/zero1.jpg">
    <img src="images/zero2.jpg">
    <img src="images/zero3.jpg">
</div>
</body>
</html>