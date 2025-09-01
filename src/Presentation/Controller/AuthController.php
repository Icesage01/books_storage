<?php

namespace src\Presentation\Controller;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use src\Models\UserModel;

class AuthController extends Controller
{
    public $layout = '@views/layouts/main';
    
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'login' => ['GET', 'POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action): bool
    {
        if ($action->id === 'login') {
            Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionLogin(): string|Response
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        if (Yii::$app->request->isPost) {
            $username = Yii::$app->request->post('username');
            $password = Yii::$app->request->post('password');
            
            if (!empty($username) && !empty($password)) {
                $user = UserModel::findByUsername($username);
                
                if (!is_null($user)) {
                    if ($user->validatePassword($password)) {
                        if (Yii::$app->user->login($user)) {
                            Yii::$app->session->setFlash('success', 'Вы успешно вошли в систему');
                            return $this->goBack();
                        } else {
                            Yii::$app->session->setFlash('error', 'Ошибка при входе в систему');
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Неверный пароль');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Пользователь не найден');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Пожалуйста, заполните все поля');
            }
        }

        return $this->render('@views/auth/login');
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success', 'Вы успешно вышли из системы');
        return $this->goHome();
    }
}
