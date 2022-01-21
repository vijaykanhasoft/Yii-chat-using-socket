<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\chat\models\ChatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Chat';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .chat
    {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .chat li
    {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dotted #B3A9A9;
    }

    .chat li.left .chat-body
    {
        margin-left: 60px;
    }

    .chat li.right .chat-body
    {
        margin-right: 60px;
    }


    .chat li .chat-body p
    {
        margin: 0;
        color: #777777;
    }

    .panel .slidedown .glyphicon, .chat .glyphicon
    {
        margin-right: 5px;
    }

    .panel-body
    {
        overflow-y: scroll;
        height: 250px;
    }

    ::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar
    {
        width: 12px;
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar-thumb
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #555;
    }
    .quadrat {
        -webkit-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Safari 4+ */
        -moz-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Fx 5+ */
        -o-animation: NAME-YOUR-ANIMATION 1s infinite;  /* Opera 12+ */
        animation: NAME-YOUR-ANIMATION 1s infinite;  /* IE 10+, Fx 29+ */
        display: block !important
    }

    @-webkit-keyframes NAME-YOUR-ANIMATION {
        0%, 49% {
            color: #e62423;
        }
        50%, 100% {
            color: #fff;
        }
    }
    .active a {
        color: #fff;
        text-decoration: none;
    }
    .active span.glyphicon-comment {
        color: #337ab7;
    }
    .quadrat a {
        color: #fff;
        text-decoration: none;
    }
</style>
<div class="container" id="ChatWindow">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-comment"></span> Chat
                </div>                
                <div class="col-md-3" style="min-height: 250px;padding: 0px;border:1px solid #ddd">
                    <div class="panel panel-default">
                        <ul class="list-group" id="contact-list">
                            <?php
                            foreach ($user_list as $user_id => $user_name) {
                                if (Yii::$app->user->identity->id !== $user_id) {
                                    ?>
                                    <li class="list-group-item" id='<?= 'USER' . $user_id ?>'>
                                        <a href="javascript:void(0)" onclick="selectUser('<?= $user_id ?>', this)">
                                            <div class="col-xs-12 col-sm-3">
                                                <img src="https://image.shutterstock.com/image-vector/male-avatar-profile-picture-use-260nw-193292033.jpg" alt="Scott Stevens" class="img-responsive img-circle" />
                                            </div>
                                            <div class="col-xs-12 col-sm-7" style="margin-top: 10px;">
                                                <span class="name"><?= $user_name ?></span><br/>
                                            </div>
                                            <span class="col-xs-12 col-sm-1 glyphicon glyphicon-comment" style="margin-top: 10px;display: none;"></span>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>

                        </ul>
                    </div>
                </div>
                <div class="panel-body col-md-9">
                    <input type='hidden' name='to' value='' />
                    <ul class="chat" id="chat"></ul>
                </div>
                <div class="panel-footer col-md-12">                    
                    <div class="col-md-3"></div>
                    <div class="input-group" class="col-md-9">
                        <input id="message" type="text" class="form-control input-sm" placeholder="Type your message here..." />
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btnSend">Send</button>
                        </span>
                    </div>
                    <div id="response" style="color:#0097cf;float: right;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
