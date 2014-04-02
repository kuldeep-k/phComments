var client_script_name = 'http://localhost/restc/client/api/client.php';
var page = 1;
function showMore(){
    //$("#comments").find("div.hide").removeClass('hide');
    //$('.more').html('');
    page++;
    getComments(article_id);
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
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
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
            url: client_script_name,
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
    //alert('2');   
    $.ajax({
        url: client_script_name,
        type: 'post',
        dataType: 'json',
        
        data: "action=getCommentsTree&tid=" + tid+'&page=' + page,
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
            
            //console.log('1123');
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
    //console.log('Here');
    for(k1 in data)
    {
        //console.log(data[k1].pid + ' -- ' + pid);
        if(data[k1].pid == pid )
        {
            //console.log(' <> ' + data[k1].id);
            htm += '<ul>';
            htm += '<li class="comment-row comment-' + data[k1].id + '">';
            htm += '<div class="row"><div class="comment">'+ data[k1].comment +'</div><div class="brow"><div class="timeago">'+ data[k1].timeago +'</div><div class="author">' + data[k1].author + '</div><div class="reply"><a href="javascript:void(0)" onClick="javascript:replyComment('+ data[k1].id +');">Reply</a></div></div></div>';
            htm = checkChild(data, data[k1].id, htm);
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
                url: client_script_name,
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
        url: client_script_name,
        type: 'post',
        dataType: 'json',
        data: "action=login&username=" + uname + "&password=" + password + "",
        success: function(response){
            if(response.status == 'success')
            {
                ckSession = new ckObject('session', {'token': response.token, 'username': uname}  );
                ckSession.set();
                getUserInfo();
                var target = $('div.User');
                if (target.length)
                {
                    var top = target.offset().top;
                    $('html,body').animate({scrollTop: top}, 1000);
                    //return false;
                }
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
    
    if(ses == null || ses == false)
    {
        //console.log('1');
        return false;
    }
    else
    {
        $.ajax({
            url: client_script_name,
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
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
    }



function loadWidget()
{
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
   // alert('1');
    getLoginInfo();
    getComments(article_id);

    //setTimeout('periodicallyCheckNewComments()', 10000);




}
