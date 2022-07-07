<?php

use common\models\MenuMain;
use yii\helpers\Html;

?>
<!-- Navigation Bar-->
<header id="topnav">
    <div class="topbar-menu">
        <div class="container-fluid">
            <div class="logo-box">
                <a href="/" class="logo text-center">
                    <span class="logo-lg">
                        <img src="/img/logo/logo.png" alt="" height="20">
                    </span>
                    <span class="logo-sm">
                        <img src="/img/logo/logo.png" alt="" height="20">
                    </span>
                </a>
            </div>
            <a class="navbar-toggle nav-link float-right">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </a>
            <div id="navigation">
                <?php if (!Yii::$app->user->isGuest): ?>
                    <!-- Navigation Menu-->
                    <?= \mix8872\menu\widgets\MenuWidget::widget([
                        'code' => 'main-menu',
                        'isParentActive' => true,
                        'menuClassName' => 'navigation-menu in',
                        'parentClassName' => 'has-submenu',
                        'submenuClassName' => 'submenu'
                    ]) ?>
                <?php endif; ?>
                <?php if (Yii::$app->user->can('admin')) : ?>
                    <?= backend\widgets\Menu::widget([
                            'options' => [
                                'class' => 'navigation-menu',
                                'tag' => false,
                            ],
                            'labelTemplate' => '{label}',
                            'isParentCssClass' => 'has-submenu',
                            'items' => [
                                [
                                    'label' => 'Контент',
                                    'icon' => 'fas fa-bars',
                                    'visible' => Yii::$app->user->can('admin'),
                                    'linkOptions' => ['class' => 'has-submenu'],
                                    'url' => '#',
                                    'items' => [
                                        [
                                            'label' => 'Контент мероприятия',
                                            'url' => ['/content/'],
                                            'icon' => 'fas fa-bars'
                                        ],
                                        [
                                            'label' => 'Меню',
                                            'icon' => 'fas fa-bars',
                                            'visible' => Yii::$app->user->can('admin'),
                                            'linkOptions' => ['class' => 'has-submenu'],
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => 'Меню для шапки (header)',
                                                    'url' => ['/main-page/menu/', 'menu' => MenuMain::MENU_HEADER],
                                                    'icon' => 'fas fa-bars'
                                                ],
                                                [
                                                    'label' => 'Меню для подвала (footer)',
                                                    'url' => ['/main-page/menu/', 'menu' => MenuMain::MENU_FOOTER],
                                                    'icon' => 'fas fa-bars'
                                                ],
                                            ]
                                        ],
                                        [
                                            'label' => 'Спикеры',
                                            'url' => ['/main-page/speaker/'],
                                            'icon' => 'fas fa-bars'
                                        ],
                                        [
                                            'label' => 'Программа',
                                            'url' => ['/main-page/program/'],
                                            'icon' => 'fas fa-bars'
                                        ],
                                        [
                                            'label' => 'Следующие мероприятия',
                                            'url' => ['/main-page/next-event/'],
                                            'icon' => 'fas fa-bars'
                                        ],
                                    ]
                                ],
                                [
                                    'label' => 'Интерактив',
                                    'icon' => 'fa fa-object-group',
                                    'linkOptions' => ['class' => 'has-submenu'],
                                    'url' => '#',
                                    'visible' => Yii::$app->user->can('admin'),
                                    'items' => [
                                        [
                                            'label' => 'Опросы',
                                            'url' => ['/interview/'],
                                            'icon' => 'fa fa-question'
                                        ],
                                        [
                                            'label' => 'Квизы',
                                            'url' => ['/quiz/'],
                                            'icon' => 'fa fa-question'
                                        ],
                                        [
                                            'label' => 'Игры "Угадай по фото"',
                                            'url' => ['/game/'],
                                            'icon' => 'fa fa-question'
                                        ],
                                    ]
                                ],
                                [
                                    'label' => 'Чат',
                                    'url' => ['/chat/'],
                                    'icon' => 'fa fa-comments'
                                ],
                                [
                                    'label' => 'Пользователи',
                                    'icon' => 'fa fa-cog',
                                    'visible' => Yii::$app->user->can('admin'),
                                    'linkOptions' => ['class' => 'has-submenu'],
                                    'url' => '#',
                                    'items' => [
                                        [
                                            'label' => 'Пользователи',
                                            'url' => ['/users/'],
                                            'icon' => 'fa fa-users'
                                        ],
                                        [
                                            'label' => 'Настройки полей пользователя',
                                            'url' => ['/user-dop-column/'],
                                            'icon' => 'fa fa-cogs'
                                        ],
                                    ]
                                ],
                                [
                                    'label' => 'Рассылки',
                                    'icon' => 'fas fa-mail-bulk',
                                    'visible' => Yii::$app->user->can('admin'),
                                    'linkOptions' => ['class' => 'has-submenu'],
                                    'url' => '#',
                                    'items' => [
                                        [
                                            'label' => 'Email рассылка',
                                            'url' => ['/mailing/send-email'],
                                            'icon' => 'fas fa-envelope'
                                        ],
                                        [
                                            'label' => 'Email шаблоны',
                                            'url' => ['/mailing/template-email'],
                                            'icon' => 'fas fa-envelope-open-text'
                                        ],
                                    ]
                                ],
                                [
                                    'label' => 'Администрирование',
                                    'icon' => 'fa fa-cog',
                                    'visible' => Yii::$app->user->can('admin'),
                                    'linkOptions' => ['class' => 'has-submenu'],
                                    'url' => '#',
                                    'items' => [
//                                        [
//                                            'label' => Yii::t('app', 'Меню'),
//                                            'url' => ['/menu/'],
//                                            'icon' => 'fa fa-bars'
//                                        ],
                                        [
                                            'label' => Yii::t('app', 'Настройки'),
                                            'url' => ['/config/'],
                                            'icon' => 'fa fa-cogs'
                                        ],
                                        [
                                            'label' => Yii::t('app', 'Пользователи'),
                                            'url' => ['/user-admin/user/'],
                                            'icon' => 'fa fa-users'
                                        ],
                                    ]
                                ],
                            ],
                        ]) ?>
                <?php endif; ?>
                <?= backend\widgets\Menu::widget([
                    'options' => [
                        'class' => 'navigation-menu float-right',
                        'tag' => false,
                    ],
                    'labelTemplate' => '{label}',
                    'isParentCssClass' => 'has-submenu',
                    'items' => [
                        [
                            'label' => '(' . Yii::$app->user->identity->signature . ') Выход',
                            'visible' => !Yii::$app->user->isGuest,
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post'],
                            'icon' => 'ti-power-off'
                        ],
                        [
                            'label' => 'Вход',
                            'visible' => Yii::$app->user->isGuest,
                            'url' => ['/site/login'],
                            'linkOptions' => ['data-method' => 'post'],
                            'icon' => 'ti-arrow-right'
                        ],
                    ]
                ]) ?>
                <!-- End navigation menu -->

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end container -->
    </div>
    <!-- end navbar-custom -->

</header>
<!-- End Navigation Bar-->
