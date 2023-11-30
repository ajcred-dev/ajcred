<?php

namespace app\models;
use yii\base\Model;

use Yii;
use \app\models\base\Busca as BaseBusca;


class ImportacaoLote extends Model
{

    /**
     * @var UploadedFile
     */
    public $arquivo;

    public function rules()
    {
        return [
            [['arquivo'], 'file', 'skipOnEmpty' => false, 'extensions' => 'csv,txt', 'checkExtensionByMimeType' => false],
        ];
    }


    public function upload()
    {
        if ($this->validate()) {
            $filePath = 'uploads/'.md5(microtime(true)).'.csv';
            $this->arquivo->saveAs($filePath);
            return $filePath;
        } else {
            return false;
        }
    }
	
}
