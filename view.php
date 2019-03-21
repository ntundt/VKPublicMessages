<?php 
    
    require_once 'request.php';
    require_once 'timeanalyze.php';
    require_once 'config.php';
    date_default_timezone_set('Europe/Minsk');
    $request = new Request(isset($_GET['access_token'])?$_GET['access_token']:ACCESS_TOKEN);
    $insert_to_head = '';
    
    function console_log($what) {
        global $insert_to_head;
        $insert_to_head .= '<script>console.log("'.$what.'")</script>';
    }
    function alert($what) {
        global $insert_to_head;
        $insert_to_head .= '<script>alert("'.$what.'");</script>';
    }
    
    function getMaxSize($where) {
        $sizes = array();
    }
    
    function drawConversations($conversations, $userdata, $groupdata = -1) {
        require_once 'finder.php';
        $userdata = new Finder($userdata);
        if($groupdata !== -1) {
            $groupdata = new Finder($groupdata);
        }
        for($i = 0; $i < count($conversations); $i++) {
            include 'markup/conversation.phtml';
        }
    }
    
    function drawMessages($messages, $userdata, $groupdata = -1) {
        require_once 'finder.php';
        $userdata = new Finder($userdata);
        if($groupdata !== -1) {
            $groupdata = new Finder($groupdata);
        }
        for($i = count($messages) - 1; $i >= 0 ; $i--) {
            include 'markup/message.phtml';
        }
    }
    
    function showError($error) {
        global $insert_to_head;
        $insert_to_head .= '<script>window.onload=function(){alert("There is an error during execution. Code: '.$error['error_code'].'; Message: '.$error['error_msg'].'")}</script>';
        var_dump($error);
    }
    
    if(!isset($_GET['peer']) and !isset($_GET['search'])) {
        $request->setMethod('messages.getConversations');
        $request->addParameter('count', 50);
        $request->addParameter('extended', 1);
        if(isset($_GET['offset'])) {
            $request->addParameter('offset', $_GET['offset']);
        }
        $request->addParameter('fields', 'photo_50,photo_100,sex,first_name_acc,last_name_acc');
        $request->perform();
        if($request->errno) {
            function drawContent($items, $userdata, $groupdata = -1) {
                drawConversations($items, $userdata, $groupdata);
            }
        } else {
            showError($request->error);
        }
    } else if(isset($_GET['peer'])) {
        $request->setMethod('messages.getHistory');
        $request->addParameter('count', 50);
        $request->addParameter('extended', 1);
        if(isset($_GET['offset'])) {
            $request->addParameter('offset', $_GET['offset']);
        }
        $request->addParameter('fields', 'photo_50,photo_100,sex,first_name_acc,last_name_acc');
        $request->addParameter('peer_id', $_GET['peer']);
        $request->perform();
        if($request->errno) {
            function drawContent($items, $userdata, $groupdata = -1) {
                drawMessages($items, $userdata);
            }
        } else {
            showError($request->error);
        }
    } else if(isset($_GET['search'])) {
        $request->setMethod('messages.search');
        $request->addParameter('count', 50);
        $request->addParameter('extended', 1);
        if(isset($_GET['offset'])) {
            $request->addParameter('offset', $_GET['offset']);
        }
        $request->addParameter('fields', 'photo_50,photo_100,sex,first_name_acc,last_name_acc');
        $request->addParameter('q', $_GET['search']);
        $request->perform();
        if($request->errno) {
            function drawContent($items, $userdata) {
                drawMessages($items, $userdata, true);
            }
        } else {
            showError($request->error);
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Title</title>
        <style>
            * {
                font-style: sans-serif;
            }
            html {
                background-color: #edeef0;
            }
            body {
                padding: 0;
                margin: 0;
                font-family: -apple-system, BlinkMacSystemFont, Roboto, Helvetica Neue, sans-serif;
            }
            .wrapper {
                width: 550px;
                margin: auto;
                height: 100%;
                margin-top: 15px;
                margin-bottom: 15px;
                box-shadow: 0 1px 0 0 #d7d8db, 0 0 0 1px #e3e4e8;
                border-radius: 2px;
                max-height: calc(100vh - 30px - 49px);
                position: relative;
            }
            .bkg {
                background-color: #fff;
                border-bottom-left-radius: 2px;
                border-bottom-right-radius: 2px;
                position: relative;
                margin-bottom: 15px;
                max-height: calc(100vh - 30px - 49px);
                min-height: calc(100vh - 30px - 49px);
                box-shadow: 0 1px 0 0 #d7d8db, 0 0 1px 1px #e3e4e8;
                top: 49px;
                overflow-y: auto;
            }
            img {
                max-width: 400px;
            }
            .message:hover {
                background-color: #f5f7fa;
            }
            .always-blue {
                background-color: #f5f7fa !important;
            }
            .message {
                height: 71px;
                background-color: #fff;
            }
            .searchform {
                height: 48px;
                border-bottom: 1px #e7e8ec solid;
                background-color: #fff;
                border-top-right-radius: 2px;
                border-top-left-radius: 2px;
                position: fixed;
                width: 550px;
                z-index: 10;
            }
            .img-container {
                padding-left: 15px;
                padding-right: 14px;
                padding-top: 11px;
                width: 50px;
            }
            .conversation-photo {
                border-radius: 50%;
                margin-top: -6px;
                width: 50px;
                height: 50px;
            }
            .msg-body {
                width: 100%;
                border-collapse: collapse;
            }
            .tbody-message {
                height: 71px;
            }
            .msg-container {
                padding-right: 15px;
                padding-top: 10px;
                padding-bottom: 10px;
                height: 100%;
                text-align: left;
                font-size: 13px;
                position: relative;
                border-top: 1px solid #e7e8ec;
            }
            .border-top {
                border-top: 1px solid #e7e8ec;
            }
            .dialog-name {
                margin-top: 3px;
                margin-bottom:7px;
                font-weight: 500;
                width: 400px;
                text-overflow: ellipsis;
                white-space: nowrap;
                overflow: hidden;
            }
            .preview-text {
                color: #656565;
                line-height: 25px;
                width: 400px;
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
            }
            .time-conv-list {
                position: absolute;
                top: 15px;
                right: 15px;
                color: #777e8c;
                opacity: 0.7;
            }
            .bottom-rounded {
                border-bottom-left-radius: 2px;
                border-bottom-right-radius: 2px;
            }
            .no-border-top {
                border-top: none !important;
            }
            .preview-text img {
                border-radius: 50%;
                vertical-align: middle;
                width: 25px;
                height: 25px;
                margin-right: 5px;
            }
            .unread-block {
                display: block;
                padding: 0 6px;
                color: #fff;
                font-size: 11px;
                right: 15px;
                bottom: 17px;
                background-color: #5181b8;
                border-radius: 18px;
                position: absolute;
                line-height: 19px;
                font-weight: 500;
            }
            .like-a-text {
                color: #000;
                text-decoration: none;
            }
            .blue-text {
                color: #4a6f97;
            }
            .user-photo {
                border-radius: 50%;
                left: 36px;
                width: 36px;
                height: 36px;
                top: 11px;
                position: absolute;
            }
            .left-part {
                width: 72px;
                position: relative;
            }
            .right-part {
                position: relative;
            }
            .user-head-block {
                line-height: 1.23;
                font-size: 12.5px;
                left: 3px;
                top: 11px;
                width: 300px;
                position: absolute;
            }
            .user-name-href {
                color: #42648b;
                font-weight: 700;
                text-decoration: none;
            }
            .user-name-href:hover {
                text-decoration: underline;
            }
            .user-head-timemark {
                color: hsla(220,8%,51%,.6);
                font-weight: 400;
            }
            .msg-head-content {
                padding-top: 26px;
                margin-left: 3px;
                line-height: 18px;
                font-size: 13px;
                max-width: 390px;
                word-wrap: break-word;
            }
            .list-item {
                height: 31px;
                text-align: middle;
            }
            .msg-body-content {
                margin-left: 3px;
                line-height: 18px;
                font-size: 13px;
                max-width: 390px;
                word-wrap: break-word;
            }
            .list-elem {
                padding-top: 7px;
                padding-bottom: 6px;
            }
            .checked-head {
                border-radius: 50%;
                position: absolute;
                left: 9px;
                top: 19px;
                opacity: 0.7;
            }
            .checked-body {
                border-radius: 50%;
                position: absolute;
                left: 9px;
                top: 2px;
                opacity: 0.7;
            }
            .check {
                display: none;
            }
            .list-head:hover .check, .list-elem:hover .check {
                display: block;
            }
            .list-container {
                margin-bottom: 5px;
                margin-top: 5px;
            }
            .timestamp {
                line-height: 18px;
                margin-top: 20px;
                margin-bottom: 13px;
                width: 100%;
                text-align: center;
                color: #828282;
                font-weight: 400;
                font-size: 12.5px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="searchform"></div>
            <div class="bkg">
                <?php 
                    drawContent(
                        $request->response['items'], 
                        $request->response['profiles'], 
                        (isset($request->response['groups'])?$request->response['groups']:-1)
                    ); 
                ?>
            </div>
        </div>
        <?= $insert_to_head; ?>
    </body>
</html>