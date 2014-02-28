<html>
<head>
<title>Sample Client Page</title>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<style>
    #comments {
        margin-top: 25px;
        padding-left: 50px;
        width: 75%;
    }

    #comments .comment-row {
        margin-top: 15px;
        font-size: 11px;
        font-family: arial;
        background-color: #e2e3e4;
        
         
    }

    #comments .comment-row .comment {
        margin: 0px;
        padding: 0px;
        min-height: 50px;       
    }

    #comments .comment-row .brow {
        text-align: right;
        border: 1px solid white;
        width: 100%;
        min-height: 20px;       
        /*width: 25%;*/
    }

    #comments .comment-row .timeago {
        /*margin-left: 78%;*/
        width: 88%;
        float:left;
        border: 1px solid white;
    }

    #comments .comment-row .author {
        float:left;
        width: 10%;
        border: 1px solid white;
        font-style: italic;    
        margin: 0px;
        padding: 0px;

    }
</style>
</head>
<h1>This is Article Heading </h1>
<br/><br/>
<article>This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body  </article>

<br/><br/>
<article>

<div id="comments"> 
    <h3>Comments : </h3>
    <div class="default-comment-row">
        <div class="comment"></div>
        <div class="brow"><div class="timeago"></div>
        <div class="author"></div></div>
    </div>
</div>

<div id="post-comment">
    <textarea id="gcomment" name="gcomment" rows=4 cols=40></textarea>
    <input type="button" class="button-gpost" name="post" value="Post" />
</div>
<script>


$(document).ready(function(){
    var article_id = 1;
    var token = 123456;


    /*$('.button-post').click(function(){
        pushComment(article_id, 0, comment)
    });*/
    $('.button-gpost').click(function(){
        pushComment(article_id, 0, $('#gcomment').val());
        getComments(article_id);
    });
    getComments(article_id);


function getComments(tid)
{
    $.ajax({
        url: 'http://localhost/restc/server/test.php',
        type: 'post',
        dataType: 'json',
        data: "action=getComments&token="+ token +"&tid=" + tid,
        success: function(response){
            $('#comments .comment-row').remove();
            for(i=0;i<response.data.length;i++)
            {
                var cl = $('#comments').find('div.default-comment-row').clone();
                cl.find('.comment').html(response.data[i].comment);
                cl.find('.timeago').html(response.data[i].timeago);
                cl.find('.author').html(response.data[i].author);
                cl.removeClass('default-comment-row').addClass('comment-row');
                cl.appendTo($('#comments'));
            }
        },
        error: function(response){
        }
    });
}


function pushComment(tid, pid, comment)
{
    $.ajax({
        url: 'http://localhost/restc/server/test.php',
        type: 'post',
        dataType: 'json',
        data: "action=push&token="+ token +"&tid=" + tid + "&comment=" + comment + '&pid=' + pid,
        success: function(response){
        },
        error: function(response){
        }
    });
}


function doLogin()
{
    $.ajax({
        url: 'http://localhost.testc',
        type: 'post',
        dataType: 'json',
        data: "action=login&username=kuldeep&password=12345",
        success: function(response){
        },
        error: function(response){
        }
    });
}

function checkLogin()
{
    $.ajax({
        url: 'http://localhost.testc',
        type: 'post',
        dataType: 'json',
        data: "action=login_check&token=abcdefgh",
        success: function(response){
        },
        error: function(response){
        }
    });
}

function doLogout()
{
    $.ajax({
        url: 'http://localhost.testc',
        type: 'post',
        dataType: 'json',
        data: "action=logout&token=abcdefgh",
        success: function(response){
        },
        error: function(response){
        }
    });
}


function getCommentsWithFocus(tid, cid)
{
    $.ajax({
        url: 'http://localhost.testc',
        type: 'post',
        dataType: 'json',
        data: "action=getComments&token=abcdefgh&tid=" + tid,
        success: function(response){
        },
        error: function(response){
        }
    });
}
});

</script>
</article>
