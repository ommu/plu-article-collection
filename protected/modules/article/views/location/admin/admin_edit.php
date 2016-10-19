<?php
/**
 * Article Locations (article-locations)
 * @var $this AdminController
 * @var $model ArticleLocations
 * @var $form CActiveForm
 * version: 0.0.1
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2016 Ommu Platform (ommu.co)
 * @created date 18 October 2016, 02:29 WIB
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */

	$this->breadcrumbs=array(
		'Article Locations'=>array('manage'),
		$model->location_id=>array('view','id'=>$model->location_id),
		'Update',
	);
?>

<div class="form">
	<?php echo $this->renderPartial('_form', array(
		'model'=>$model,
		'tags'=>$tags,
		'users'=>$users,
	)); ?>
</div>