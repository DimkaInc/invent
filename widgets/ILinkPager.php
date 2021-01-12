<?php

namespace app\widgets;

use Yii;
use Yii\helpers\Html;
use Yii\widgets\LinkPager;

class ILinkPager extends LinkPager
{
    public function __construct() {
        $this->firstPageLabel = Html::tag('span', '', [ 'class' => 'glyphicon glyphicon-fast-backward', 'title' => Yii::t('app', 'First page'), ]);
        $this->prevPageLabel  = Html::tag('span', '', [ 'class' => 'glyphicon glyphicon-backward', 'title' => Yii::t('app', 'Previous page'), ]);
        $this->nextPageLabel  = Html::tag('span', '', [ 'class' => 'glyphicon glyphicon-forward', 'title' => Yii::t('app', 'Next page'), ]);
        $this->lastPageLabel  = Html::tag('span', '', [ 'class' => 'glyphicon glyphicon-fast-forward', 'title' => Yii::t('app', 'Last page'), ]);
    }
}

