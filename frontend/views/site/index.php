<?php

use common\models\Message;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $messages Message[] */
/* @var $messageNew Message */

$this->title = 'My Yii Application';
$formatter = Yii::$app->formatter;
?>

<div class="site-index">
  <div class="body-content">
      <?php foreach ($messages as $message): ?>
          <div class="message-container
            <?php
                if(Yii::$app->authManager->checkAccess($message->user->id, 'isAdmin')) {
                    echo 'admin';
                }
                if($message->is_banned) {
                    echo ' banned';
                }
            ?>
          ">
              <p class="user-name">
                  <?= Html::encode($message->user->username) ?>
                  <?php
                      if(Yii::$app->user->can('isAdmin')):
                         if(!$message->is_banned):
                  ?>
                          <a href="<?= Url::to(['site/moderate', 'messageId' => $message->id, 'isBanned' => 1]) ?>" title="Deny message" aria-label="Deny" data-pjax="0" data-confirm="Are you sure you want to deny this message?" data-method="post">
                            <span class="glyphicon glyphicon-remove ban-btn"></span>
                          </a>
                      <?php else: ?>
                          <a href="<?= Url::to(['site/moderate', 'messageId' => $message->id, 'isBanned' => 0]) ?>" title="Allow message" aria-label="Allow" data-pjax="0" data-confirm="Are you sure you want to allow this message?" data-method="post">
                              <span class="glyphicon glyphicon-open ban-btn"></span>
                          </a>
                      <?php endif; ?>
                  <?php endif; ?>
              </p>
              <p><?= Html::encode($message->text) ?></p>
              <span class="date-right"><?= $formatter->asDatetime($message->created_at, 'php:d/m/Y H:i:s') ?></span>
          </div>
      <?php endforeach; ?>

      <?php if(Yii::$app->user->can('write')): ?>
          <div class="message-form">

              <?php $form = ActiveForm::begin(); ?>

              <?= $form->field($messageNew, 'text')->textarea(['rows' => 6])->label('Your Message') ?>


              <div class="form-group" style="text-align: right">
                  <?= Html::submitButton('Send', ['class' => 'btn btn-info']) ?>
              </div>

              <?php ActiveForm::end(); ?>

          </div>
      <?php endif; ?>
    </div>
</div>

<?php
$style = <<<CSS
      /* Chat containers */
    .message-container {
        border: 2px solid #dedede;
        background-color: #f1f1f1;
        border-radius: 5px;
        padding: 10px;
        margin: 10px 0;
    }
    
    .message-container.admin {
        border-color: #1c38c352;
        background-color: #1c38c352;
    }
    
    .message-container.banned {
        border-color: #da09095e;
        background-color: #da09095e;
    }
    
    /* Clear floats */
    .message-container::after {
        content: "";
        clear: both;
        display: table;
    }
    
     /* Clear floats */
    .pull-right::after {
        content: "";
        clear: both;
        display: table;
    }
    
    .message-container p {
        padding: 15px;
        font-size: medium;

    }
    
    .date-right {
        float: right;
        color: #999;
    }
    
    .user-name {
        color: blueviolet;
        border-bottom: 1px solid blueviolet;
        font: message-box;
    }
    
    .admin .user-name {
        color: red;
        border-bottom-color: red;
    }
    
    .ban-btn {
        float: right;
        color: red;
    }
    
    .banned .ban-btn {
        color: #1c7430;
    }
  
CSS;

  $this->registerCss($style);
