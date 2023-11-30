<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>


    <style>
        .ajcredbar{
            background-color: #EA9957;
        }
    </style>


</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php

    $logo = '<svg class="svg-logo-pc" width="127" height="48" viewBox="0 0 127 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.89699 24.6408L0 29.1352L8.1794 11.5226C8.35816 11.1344 8.74632 10.8867 9.17278 10.8867H22.1403C22.5668 10.8867 22.9549 11.1344 23.1362 11.5226L31.3054 29.1352L22.4876 24.6944L16.9257 13.1569C16.701 12.6922 16.2311 12.3985 15.7178 12.401C15.2097 12.401 14.75 12.6922 14.5253 13.1467L8.89699 24.6382V24.6408Z" fill="white"></path><path d="M0 29.3135V37.1124L14.9185 29.5484C15.3808 29.3135 15.9272 29.3135 16.3895 29.5484L31.308 37.1124V29.3135C31.308 29.2037 31.2467 29.1041 31.1497 29.053L16.392 21.5707C15.9298 21.3358 15.3833 21.3358 14.9211 21.5707L0.158328 29.053C0.0612881 29.1015 0 29.2037 0 29.3135Z" fill="#FF5D01"></path><path d="M58.8542 16.4171C59.4824 14.415 60.629 13.8711 62.4319 13.8711C64.2348 13.8711 65.3788 14.415 66.0096 16.4171L71.5612 34.1294H67.5852L66.0964 29.0654H58.7418L57.2837 34.1294H53.3076L58.8542 16.4171ZM59.4262 26.3739H65.4069L63.0039 18.22C62.8328 17.648 62.6898 17.3901 62.4319 17.3901C62.174 17.3901 62.031 17.648 61.8599 18.22L59.4288 26.3739H59.4262Z" fill="white"></path><path d="M72.0996 31.1243H72.8146C75.9633 31.1243 77.0486 29.6943 77.0486 26.4026V14.1016H80.8255V27.4062C80.8255 31.298 78.9664 34.13 74.6456 34.13H72.0996V31.1269V31.1243Z" fill="white"></path><path d="M89.7254 21.5389C86.2065 21.5389 86.1477 23.6559 86.1477 26.5186C86.1477 29.3813 86.2039 31.5544 89.7254 31.5544H93.0145V34.1285H88.8955C83.7447 34.1285 82.6875 31.0948 82.6875 26.5186C82.6875 21.9424 83.7473 18.9648 88.8955 18.9648H92.6724V21.5389H89.7254Z" fill="white"></path><path d="M94.1865 24.6857C94.1865 20.6228 95.9613 18.9629 100.024 18.9629H101.426V21.537H100.882C98.1932 21.537 97.5344 22.8828 97.5344 25.5718V34.1266H94.1865V24.6857Z" fill="white"></path><path d="M107.006 34.1297C102.427 34.1297 101.283 31.382 101.283 26.5198C101.283 20.511 102.598 18.7949 107.463 18.7949C111.47 18.7949 113.615 19.939 113.615 24.8037C113.615 26.5198 112.984 27.6638 111.268 27.6638H104.743C104.743 29.9519 105.43 31.5556 108.004 31.5556H113.127V34.1297H107.003H107.006ZM109.409 25.0872C110.068 25.0872 110.152 24.6301 110.152 24.201C110.152 22.199 109.179 21.369 107.463 21.369C105.175 21.369 104.746 22.5131 104.746 25.0897H109.409V25.0872Z" fill="white"></path><path d="M126.978 31.5533C126.978 33.2694 126.12 34.1274 124.404 34.1274H120.54C115.675 34.1274 114.618 31.0937 114.618 26.5175C114.618 21.9413 115.678 18.9637 120.54 18.9637H123.63V14.1016H126.978V31.5533ZM121.37 21.5378C118.137 21.5378 118.078 23.6548 118.078 26.5175C118.078 29.3802 118.135 31.5533 121.37 31.5533H122.772C123.344 31.5533 123.63 31.2673 123.63 30.6953V21.5404H121.37V21.5378Z" fill="white"></path></svg>';


    NavBar::begin([
        'brandLabel' => $logo,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'ajcredbar navbar-expand-md fixed-top']
    ]);
    
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; AJCRED <?= date('Y') ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
