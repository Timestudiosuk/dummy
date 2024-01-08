<?php
/**
 * Shipper modal dialogs: New Features
 *
 * @since 1.2
 *
 * @package shipper
 */

$task  = new Shipper_Task_Check_Newfeatures();
$model = new Shipper_Model_Newfeatures();

if ( $task->apply() ) {
	$this->render(
		'modals/new-features',
		array(
			'title'       => $model->get_title(),
			'description' => $model->get_description(),
			'features'    => $model->get_features(),
		)
	);
}