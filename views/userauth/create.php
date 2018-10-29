<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserAuth */

$this->title = 'Create User Auth';
$this->params['breadcrumbs'][] = ['label' => 'User Auths', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-auth-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
