<?php

namespace ApiModule;

/**
 * BasePresenter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
interface ApiPresenter {

	public function actionDefault();
	public function actionCreate();
	public function actionUpdate();
	public function actionDelete();
}
