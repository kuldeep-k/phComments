<html>
<head>
<title>Sample Client Page</title>
</head>
<h1>This is Article Heading </h1>
<br/><br/>
<article>This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body  </article>

<br/><br/>
<article>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="client/widget.js"></script>

<script>
//$('.ch-cont').load('client/widget.html');

var article_id = 2;

var xhr = new XMLHttpRequest();

xhr.onload = function () {
    document.getElementById('ch-cont').innerHTML = this.response;
    loadWidget();
    
};

xhr.open('GET', 'client/widget.html', true);
xhr.send();

</script>
<?php

echo time();

?>
<div id="ch-cont">

</div>


</article>
