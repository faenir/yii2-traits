<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-traits
 * @license GNU GENERAL PUBLIC LICENSE VERSION 3
 * @package yii2-traits
 * @version 0.1.0
 */

namespace cinghie\traits;

use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\data\ArrayDataProvider;
use yii\web\HttpException;

trait CacheTrait
{

    /**
     * @return mixed
     */
    public function actionCache()
    {
        $dataProvider = new ArrayDataProvider(['allModels'=>$this->findCaches()]);
        return $this->render('cache', ['dataProvider'=>$dataProvider]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionFlushCache($id)
    {
        if ($this->getCache($id)->flush()) {
            \Yii::$app->session->setFlash('success', \Yii::t('essentials', 'Cache has been successfully flushed'));
        };
        return $this->redirect(['cache']);
    }

    /**
     * @param $id
     * @param $key
     * @return \yii\web\Response
     */
    public function actionFlushCacheKey($id, $key)
    {
        if ($this->getCache($id)->delete($key)) {
            \Yii::$app->session->setFlash('success', \Yii::t('essentials', 'Cache entry has been successfully deleted'));
        };
        return $this->redirect(['cache']);
    }

    /**
     * @param $id
     * @param $tag
     *
     * @return \yii\web\Response
     */
    public function actionFlushCacheTag($id, $tag)
    {
        TagDependency::invalidate($this->getCache($id), $tag);
        \Yii::$app->session->setFlash('success', \Yii::t('essentials', 'TagDependency was invalidated'));
        return $this->redirect(['cache']);
    }

    /**
     * @param $id
     * @return \yii\caching\Cache|null
     * @throws HttpException
     */
    protected function getCache($id)
    {
        if (!in_array($id, array_keys($this->findCaches()))) {
            throw new HttpException(400, 'Given cache name is not a name of cache component');
        }
        return \Yii::$app->get($id);
    }

    /**
     * Returns array of caches in the system, keys are cache components names, values are class names.
     *
     * @param array $cachesNames caches to be found
     * @return array
     */
    private function findCaches(array $cachesNames = [])
    {
        $caches = [];
        $components = \Yii::$app->getComponents();
        $findAll = ($cachesNames == []);
        foreach ($components as $name => $component) {
            if (!$findAll && !in_array($name, $cachesNames)) {
                continue;
            }
            if ($component instanceof Cache) {
                $caches[$name] = ['name'=>$name, 'class'=>get_class($component)];
            } elseif (is_array($component) && isset($component['class']) && $this->isCacheClass($component['class'])) {
                $caches[$name] = ['name'=>$name, 'class'=>$component['class']];
            } elseif (is_string($component) && $this->isCacheClass($component)) {
                $caches[$name] = ['name'=>$name, 'class'=>$component];
            }
        }
        return $caches;
    }

    /**
     * Checks if given class is a Cache class.
     *
     * @param string $className class name.
     * @return boolean
     */
    private function isCacheClass($className)
    {
        return is_subclass_of($className, Cache::className());
    }

}