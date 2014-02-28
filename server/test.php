<?php

date_default_timezone_set('Asia/Calcutta');

function convert_ds($date)
{
    $t = strtotime($date);
    $ct = time();
    //echo $t.' '.$ct; 
    if($t >= $ct - 60 )
    {
        $tm = (int)($ct - $t);
        $st = $tm==1?$tm.' Second Ago' : $tm.' Seconds Ago';
    }
    else if($t >= $ct - 3600 )
    {
        $tm =  ((int)(($ct - $t) / 60));
        $st =  $tm==1?$tm.' Minute Ago' : $tm.' Minutes Ago';
    }
    else if($t >= $ct - (3600 * 24) )
    {
        $tm = ((int)(($ct - $t) / 3600));
        $st =  $tm==1?$tm.' Hour Ago':$tm.' Hours Ago';
    }
    else if($t >= $ct - (3600 * 24 * 365) )
    {
        $tm = ((int)(($ct - $t) / (3600 * 24)));
        $st =  $tm==1?$tm.' Day Ago':$tm.' Days Ago';
    }
    else
    {
        $tm = ((int)(($ct - $t) / (3600 * 24)));
        $st =  $tm==1 ? $tm.' Year Ago':$tm.' Years Ago';
    }
    //echo ' '.$st.PHP_EOL; 
    return $st; 
} 
try
{
$d = new mysqli('localhost', 'root', 'root', 'commt');

$server_method = $_SERVER['REQUEST_METHOD'];

$action = $_REQUEST['action'];

switch($action)
{
    case "push":
        $d->query("INSERT INTO comments (article_id, parent_id, comment, author_id, created_at, updated_at) VALUES ('".$_REQUEST['tid']."', '".$_REQUEST['pid']."', '".$_REQUEST['comment']."',  '".$_REQUEST['aid']."', now(), now()) ");
        $content = array('status' => 'success');
        break;
    case "getComments":
        $rs = $d->query("SELECT c.id, c.comment, c.created_at, u.username as author FROM comments c LEFT JOIN user u ON c.author_id = u.id WHERE c.article_id = '".$_REQUEST['tid']."' ORDER BY c.created_at DESC ");
        $data = array();
        
        while($row = $rs->fetch_assoc())
        {
            $row['comment'] = utf8_encode($row['comment']);
            $row['timeago'] = convert_ds($row['created_at']);
            $data[] = $row;
        }
        $content = array('status' => 'success', 'data' => $data);
        break;
}


header('content-type: application/json');
echo json_encode($content);

}
catch(Exception $e)
{
    header('HTTP/1.1 500 Error : '.$e->getMessage());
}
die;
/*

CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_key varchar(255) NOT NULL,
    username varchar(255) NOT NULL,
    password varchar(255) NOT NULL    
);

CREATE TABLE user_session (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username varchar(255) NOT NULL,
    session_token varchar(255) NOT NULL,
    token_created datetime,
    token_expired tinyint(1)        
);

CREATE TABLE domain (
    id INT PRIMARY KEY AUTO_INCREMENT,
    domain varchar(255) NOT NULL
);

CREATE TABLE article (
    id INT PRIMARY KEY AUTO_INCREMENT,
    domain_id INT, 
    article varchar(255) NOT NULL
);

CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    article_id INT, 
    parent_id INT, 
    comment varchar(255) NOT NULL,
    author_id INT 
);


*/


