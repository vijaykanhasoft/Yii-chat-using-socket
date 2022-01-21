<?php

namespace frontend\modules\chat\controllers;

use Yii;
use frontend\modules\chat\models\Chat;
use frontend\modules\chat\models\ChatSearch;
use backend\modules\user\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * DefaultController implements the CRUD actions for Chat model.
 */
class DefaultController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Chat models.
     * @return mixed
     */
    public function actionIndex() {

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        $users = User::find()->all();
        $userList = [];
        if (count($users) > 0) {
            foreach ($users as $user) {
                $userList[$user->id] = $user->username;
            }
        }

        $searchModel = new ChatSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'user_list' => $userList,
        ]);
    }

    /**
     * Displays a single Chat model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Chat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Chat();
        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            echo json_encode(array('success' => true, 'data' => Yii::$app->request->post()));
        } else {
            echo json_encode(array('success' => false));
        }
        exit;
    }

    /**
     * Updates an existing Chat model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->chat_message_id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Chat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Chat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Chat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Chat::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChatdata() {
        $Query = "SELECT chat_messages.* ,user.username 
                    FROM `chat_messages` 
                    LEFT JOIN `user` 
                        ON user.id = chat_messages.message_from  
                    WHERE (chat_messages.message_from =" . Yii::$app->request->post('user_id') . " AND chat_messages.message_to = " . Yii::$app->user->identity->id . ") 
                            OR (chat_messages.message_from =" . Yii::$app->user->identity->id . " AND chat_messages.message_to = " . Yii::$app->request->post('user_id') . ")";
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($Query);
        $chatList = $command->queryAll();
        echo Json::encode(array('result' => $chatList));
        exit;
    }

}
