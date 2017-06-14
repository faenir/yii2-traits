<?php

namespace cinghie\traits;

use dosamigos\ckeditor\CKEditor;
use dosamigos\tinymce\TinyMce;
use Yii;
use yii\imperavi\Widget as Imperavi;

/**
 * Trait EditorTrait
 *
 * @property integer $access
 */
trait EditorTrait
{

    /**
     * Generate Editor Widget
     *
     * @param \kartik\widgets\ActiveForm $form
     * @param string $field
     * @param string $requestEditor
     * @return \kartik\form\ActiveField
     * @internal param string $editor
     */
    public function getEditorWidget($form,$field,$requestEditor = "")
    {
        $editor = $requestEditor != "" ? $requestEditor : Yii::$app->controller->module->editor;

        switch ($editor)
        {
            case "ckeditor":
                return $this->getCKEditorWidget($form,$field);
                break;
            case "tinymce":
                return $this->getTinyMCEWidget($form,$field);
                break;
            case "imperavi":
                return $this->getImperaviWidget($form,$field);
                break;
            default:
                return $this->getNoEditorWidget($form,$field);
        }
    }

    /**
     * Get a CKEditor Editor Widget
     *
     * @param \kartik\widgets\ActiveForm $form
     * @param string $field
     * @return \kartik\form\ActiveField
     */
    public function getCKEditorWidget($form,$field)
    {
        /** @var $this \yii\base\Model */
        return $form->field($this, $field)->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'advanced'
        ]);
    }

    /**
     * Get a TinyMCE Editor Widget
     *
     * @param \kartik\widgets\ActiveForm $form
     * @param string $field
     * @return \kartik\form\ActiveField
     */
    public function getTinyMCEWidget($form,$field)
    {
        /** @var $this \yii\base\Model */
        return $form->field($this, $field)->widget(TinyMce::className(), [
            'options' => ['rows' => 6],
            'clientOptions' => [
                'plugins' => [
                    "advlist autolink lists link charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            ]
        ]);
    }

    /**
     * Get a Imperavi Editor Widget
     *
     * @param \kartik\widgets\ActiveForm $form
     * @param string $field
     * @return \kartik\form\ActiveField
     */
    public function getImperaviWidget($form,$field)
    {
        /** @var $this \yii\base\Model */
        return $form->field($this, $field)->widget(Imperavi::className(), [
            'options' => [
                'css' => 'wym.css',
                'minHeight' => 250,
            ],
            'plugins' => [
                'fullscreen',
                'clips'
            ],
        ]);
    }

    /**
     * Get a No-Editor Widget
     *
     * @param \kartik\widgets\ActiveForm $form
     * @param string $field
     * @return \kartik\form\ActiveField
     */
    public function getNoEditorWidget($form,$field)
    {
        /** @var $this \yii\base\Model */
        return $form->field($this, $field)->textarea(['rows' => 6]);
    }

}