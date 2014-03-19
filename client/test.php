<html>
<head>
<title>Sample Client Page</title>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<style>
    
	article {    
        border: 1px solid #000000;
        font-family: arial;
        font-size: 12px;
        line-height: 20px;
        padding: 15px;
        width: 870px;
    }

    .User {
        font-weight: bold;
    }
    #login-box {
        width: 40%;
        border: 1px solid #000;
        padding: 10px;
    }
    #comments {
        margin-top: 25px;
        padding-left: 50px;
        width: 75%;
    }
    #comments .default-comment-row {
        list-style-type: none;
    }

    #comments .comment-row {
        list-style-type: none;
        margin-top: 15px;
    }

    #comments .comment-row .row {
        padding: 10px;
        color: #4a6687;
        font-size: 11px;
        font-family: arial;
        background-color: #e2e3e4;
        border-radius: 15px;
        box-shadow: 2px 2px 3px 3px #ccc;
        /*transform: skew(-10deg);
        -webkit-transform: skew(-10deg);*/
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

    #comments .comment-row .reply {
        text-align: right;
        padding-right: 10px;        
        
    }
    #comments .comment-row .reply a {
        color: #7788a0;
        text-decoration: none;
    }

    #comments .comment-row .reply a:hover {
        color: #7788a0;
        text-decoration: underline;
        font-style: italic;
    }

    .hide {
        display: none;            
    }
</style>

<script>

var article_id = 2;

function showMore(){
    $("#comments").find("div.hide").removeClass('hide');
    $('.more').html('');
}

function userSignOut()
{
    var ses = ckSession.get('session');
    if(ses == null)
    {
        //console.log('1');
        return false;
    }
    else
    {
        ckSession.unset('session');
        //removeSession(name);   
        $('.User').html('Anonymous (post not allowed).'); 
    }
}

function ckObject(name, value, expires)
{
    this.name = name;
    this.value = value;
    this.expires = expires;
} 


function getSession()
{
    name = "session";
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");

    if (parts.length == 2) 
    {
        p = JSON.parse(parts.pop().split(";").shift());
        //return new JSON.parse(p);
        return new ckObject(name, p, null);
    }
    else
    {
        return new ckObject(null, null, null);
    }
}

function removeSession(name)
{
    document.cookie = name + '=; expires='+ this.expires + '; path=/';
}


function getLoginInfo()
{
    if(checkLogin() == false)
    {
        $('.User').html('Anonymous (post not allowed).');
    }
    else
    {
        getUserInfo();
    }
}

function getUserInfo()
{
    var ses = ckSession.get('session');
    if(ses == null)
    {
        return false;
    }
    else
    {
        $.ajax({
            url: 'http://localhost/restc/server/test.php',
            type: 'post',
            dataType: 'json',
            async: false,
            data: "action=getUserInfo&username="+ ses.username +"&token=" + ses.token,
            success: function(response){
                $('.User').html(response.data.username + ' - <a href="javascript:void(0);">Sign out</a>' );
                $('.User a').click(function(){
                    var ses = ckSession.get('session');
                    ckSession.unset('session');
                    //removeSession(name);   
                    $('.User').html('Anonymous (post not allowed).'); 

                    $.ajax({
                        url: 'http://localhost/restc/server/test.php',
                        type: 'post',
                        dataType: 'json',
                        data: "action=logout&username=" + ses.uname + "&token=" + ses.token + "",
                        success: function(response){
                        },
                        error: function(response){
                        }
                    });
                   
                });
                
            },
            error: function(response){
            }
        });
    }
}

function getComments(tid)
{
    $.ajax({
        url: 'http://localhost/restc/server/test.php',
        type: 'post',
        dataType: 'json',
        
        data: "action=getComments&tid=" + tid,
        success: function(response){
            $('#comments li.comment-row').remove();
            var htm = '';
            /*for(i=0;i<response.data.length;i++)
            {
                
                var cl = $('#comments').find('li.default-comment-row').clone();
                cl.find('.comment').html(response.data[i].comment);
                cl.find('.timeago').html(response.data[i].timeago);
                cl.find('.author').html(response.data[i].author);
                cl.removeClass('default-comment-row').addClass('comment-row');
                cl.addClass('comment-' + response.data[i].id);
                if(i > 5)
                {
                    cl.addClass('hide');
                }
                cl.append('<div class="reply"><a href="javascript:void(0)" onClick="javascript:replyComment('+ response.data[i].id +');">Reply</a>');
                
                cl.appendTo($('#comments ul'));
                
                
            }*/
            for(i=0;i<response.data.length;i++)
            {
                if(response.data[i].pid == 0)
                {
                                        
                    htm += '<li class="comment-row comment-' + response.data[i].id + '">';
                    htm += '<div class="row"><div class="comment">'+ response.data[i].comment +'</div><div class="brow"><div class="timeago">'+ response.data[i].timeago +'</div><div class="author">' + response.data[i].author + '</div><div class="reply"><a href="javascript:void(0)" onClick="javascript:replyComment('+ response.data[i].id +');">Reply</a></div></div></div>';
                    htm = checkChild(response.data, response.data[i].id, htm);
                    htm += '</li>';   
                    
                }
                
            }
            $('#comments ul').append($(htm));
            if(response.data.length > 5)
            {
                $('.more').html('<a href="javascript:void(0)" onClick="javascript:showMore();">More ....</a>');
            }
        },
        error: function(response){
        }
    });
}

function addRow()
{

    
}
function addRow1(data_row)
{
    var cl = $('#comments').find('li.default-comment-row').clone();
    cl.find('.comment').html(data_row.comment);
    cl.find('.timeago').html(data_row.timeago);
    cl.find('.author').html(data_row.author);
    cl.removeClass('default-comment-row').addClass('comment-row');
    cl.addClass('comment-' + data_row.id);
    cl.append('<div class="reply"><a href="javascript:void(0)" onClick="javascript:replyComment('+ data_row.id +');">Reply</a>'); 
    return cl;
} 

function checkChild(data, pid, htm) 
{
    for(k1 in data)
    {
        if(data[k1].pid == pid )
        {
            console.log(data[k1].pid);
            htm += '<ul>';
            htm += '<li class="comment-row comment-' + data[k1].id + '">';
            htm += '<div class="row"><div class="comment">'+ data[k1].comment +'</div><div class="brow"><div class="timeago">'+ data[k1].timeago +'</div><div class="author">' + data[k1].author + '</div><div class="reply"><a href="javascript:void(0)" onClick="javascript:replyComment('+ data[k1].id +');">Reply</a></div></div></div>';
            checkChild(data, data[k1].id, htm);
            htm += '</li></ul>';
        }
    }   
    return htm;
}

function replyComment(pid) {
    var reply_html = '<div id="post-reply"><textarea class="rcomment" name="rcomment" rows=4 cols=40></textarea><input type="button" class="button-rpost" name="post" value="Post" ></div>';
        
    $(reply_html).appendTo($('#comments .comment-' + pid + ' .row:first'));
    $('#comments .comment-' + pid).find('.button-rpost').click(function(){
        pushComment(article_id, pid, $('#comments .comment-' + pid).find('textarea.rcomment').val());
    });
}

function pushComment(tid, pid, comment)
{
    var st = checkLogin();    
    //alert(st);    
    if(st == true)
    {
        var ses = ckSession.get('session');
        if(ses == null)
        {
            //console.log('1');
            return false;
        }
        else
        {
            $.ajax({
                url: 'http://localhost/restc/server/test.php',
                type: 'post',
                dataType: 'json',
                data: "action=push&token="+ ses.token +"&tid=" + tid + "&comment=" + comment + '&pid=' + pid + '&username=' + ses.username,
                success: function(response){
                    getComments(tid);
                },
                error: function(response){
                }
            });
        }
    }
    else
    {
        alert('Not authorized to post comments');
    }
}

function openLoginBox()
{
}

function closeLoginBox()
{
}

function doLogin()
{
    var uname = $('#uname').val();
    var password = $('#upass').val();

    $.ajax({
        url: 'http://localhost/restc/server/test.php',
        type: 'post',
        dataType: 'json',
        data: "action=login&username=" + uname + "&password=" + password + "",
        success: function(response){
            if(response.status == 'success')
            {
                ckSession = new ckObject('session', {'token': response.token, 'username': uname}  );
                ckSession.set();
                //token = response.token;
                //username = uname;
            }
            else
            {
                alert('Login Failed');
            }
        },
        error: function(response){
        }
    });
}


function checkLogin()
{
    var st = false;
    var ses = ckSession.get('session');
    if(ses == null)
    {
        //console.log('1');
        return false;
    }
    else
    {
        $.ajax({
            url: 'http://localhost/restc/server/test.php',
            type: 'post',
            dataType: 'json',
            async: false,
            data: "action=login_check&username="+ ses.username +"&token=" + ses.token,
            success: function(response){
                if(response.status == 'success' && response.login == 1)
                {
                    st = true;
                }
            },
            error: function(response){
                return false;
            }
        });
        //console.log(st);
        return st;
    }
}


function periodicallyCheckNewComments()
{
    getComments(article_id);
    //setTimeout('periodicallyCheckNewComments()', 10000);
}

var ckSession = new Object();

$(document).ready(function(){

    ckObject.prototype.set = function(){
        if(typeof this.value == 'object')
        {
            var str = '';
            document.cookie = this.name + '=' + JSON.stringify(this.value) +'; expires='+ this.expires + '; path=/';
        }
        else
        {
            //document.cookie = this.name + '=' + this.value +'; expires='+ this.expires + '; path=/';
        }
    }

    ckObject.prototype.get = function(name){
        
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2) 
        {
            p = parts.pop().split(";").shift();
            return JSON.parse(p);
        }
        return false;
    }

    ckObject.prototype.unset = function(name){
        document.cookie = name + '=; expires='+ this.expires + '; path=/';
    }

    ckSession = getSession();
    
    console.log(ckSession);

    /*$('.button-post').click(function(){
        pushComment(article_id, 0, comment)
    });*/
    $('.button-gpost').click(function(){
        pushComment(article_id, 0, $('#gcomment').val());
    });
/*    $('.button-login').click(function(){
        $('#login-box').removeClass('hide');
        //doLogin('admin', 'admin');
    });
*/
    getLoginInfo();
    getComments(article_id);

    //setTimeout('periodicallyCheckNewComments()', 10000);

});

</script>
</head>
<h1>This is Article Heading </h1>
<br/><br/>
<article>This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body This is Article Body  </article>

<br/><br/>
<article>

<div class="User">

</div>

<div id="comments"> 
    <h3>Comments : </h3>
    <ul>
    <li class="default-comment-row">
        <div class="row">
            <div class="comment"></div>
            <div class="brow"><div class="timeago"></div>
            <div class="author"></div></div>
        </div>
    </li>
    </ul>
</div>
<div class="more"></div>

<div id="post-comment">
    <textarea id="gcomment" name="gcomment" rows=4 cols=40></textarea>
    <input type="button" class="button-gpost" name="post" value="Post" />
</div>

<div id="login-box" class="hide">
    <label>Username : </label><input type="text" id="uname" name="uname" /> <br/>
    <label>Password : </label><input type="password" id="upass" name="upass" /> <br/>
    <input type="button" class="button-login" name="login" onClick="doLogin();" value="Login" />
</div>
    <input type="button" class="button-toogleloginbox" name="toogleloginbox" onClick="javascript:$('div#login-box').removeClass('hide');" value="Login" />



 <div id="disqus_thread"></div>
    <!--<script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'kuldeep15'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
    

<script type="text/javascript">
			  var disqus_shortname = 'dnai';
			  var disqus_identifier = 1966450;
			  (function(){
				var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
				dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
			  })();
			</script>
-->
</article>
