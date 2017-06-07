<?php
/*
 *
 * Copyright 2017 ELASTIC Consultants Inc.
 *
 */

use Cake\Event\Event;
use Cake\Event\EventManager;

EventManager::instance()->on('Bake.initialize', function (Event $event) {
    $view = $event->getSubject();
    /* @var $view Bake\View\BakeView */
    $view->loadHelper('MyBake.ModelDocBlock');
});
