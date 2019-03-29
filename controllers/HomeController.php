<?php

namespace reward\controllers;

class HomeController extends \yii\web\Controller
{
    public function actionHome()
    {
        return $this->render('home');
    }

}
