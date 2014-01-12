
<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'division-grid',
//        'hideHeader' => true,
        'dataProvider' => $model->search(),
        'filter' => $model,
        'enablePagination'=>true,
        'columns' => array(
          array(
            'name' => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("contentActions/show", array("id" => $data->id)))',
            'type' => 'raw'
          ),
          array(
            'name' => 'type',
            'value' => '$data->type == 2 ? "bla" : "blabla"',
            'filter' => CHtml::listData(content::model()->findAll(), 'type', function($model) {
              if($model->type == 0) {
                return 'Easy';
              }else if($model->type == 1) {
                return 'Medium';
              } else {
                return 'Hard';
              }
            })
          ),
          array(
            'name' => 'date',
            'filter' => CHtml::dropDownList('type', null, array('last_week' => 'Week', 'last_year' => 'Year', 'all_time' => 'All  time'))
          )
        ),
)); ?>