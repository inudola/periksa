<?php
namespace reward\controllers;
use reward\models\Employee;
use yii\web\Controller;

/**
 * Site controller
 */
class SearchController extends Controller
{
    /**
     * {@inheritdoc}
     */
    
    public static function allowedDomains() {
        return [
         '*',                        // star allows all domains
          
        ];
    }
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [

            // For cross-domain AJAX request
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin'                           => static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 86400,                 // Cache (seconds)
                ],
            ],
    
        ]);
        

    }

    /**
     * {@inheritdoc}
     */


    /**
     * Displays homepage.
     *
     * @return string
     */


    

    /**
     * Logout action.
     *
     * @return string
     */

    public function actionOptions()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $totalReward = Employee::instance()->getTotalReward();

        return $this->asJson($totalReward);
    
        
    }  
}
