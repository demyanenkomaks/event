<?php

namespace common\classes;

use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class SortableGridAction extends Action
{
    public $modelName;

    /**
     * Precess elements sorting
     * @return bool[]|false[]
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($data = Yii::$app->request->post('sort')) {
            $success = true;
            $idArr = ArrayHelper::getColumn($data, 'id');
            try {
                $models = $this->modelName::find()->where(['id' => $idArr])->indexBy('id')->all();
                foreach ($data as $dataItem) {
                    if (!$models[$dataItem['id']]) {
                        continue;
                    }
                    $models[$dataItem['id']]->order = ($dataItem['order'] + 1);
                    if (!$models[$dataItem['id']]->save()) {
                        $success = false;
                    }
                }
                return ['success' => $success];
            } catch (\Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }
        return ['success' => false];
    }
}
