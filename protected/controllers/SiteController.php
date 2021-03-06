<?php

// load twitter authentication
require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// get all places from db
		$places = Place::model()->findAll();

		// *** twitter api *** //
		//twitter authentication
		$connection = new TwitterOAuth("8gpWBVBSfGB2clOm8thLz29yu", "KkSJTv5chO4e6AzGsOkctKnk8nnFjdHzXzISDLM48AZ91gneGG", "3299182320-gipHvZakrdUnQIzfLVP4D5i4uP34vxfqSabxHny", "7D93mzbvVxwujnH20vYbuCPM8LRpZ1vfhCq4LEW5bwGrA");
		$content = $connection->get("account/verify_credentials");
		// search tweets
		$tweets = $connection->get("search/tweets", array("q" => "chiangmai"));
		// *** end twitter api *** //

		// weather api
		$weather = json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=chiangmai&units=metric"));

		// render index page and pass places, tweets, and weather
		$this->render('index', array('places' => $places,'tweets' => $tweets, 'weather' => $weather));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	// view a single place
	public function actionView($id) {		// require a place id as param
		// retrieve a place from db using the id
		$places = Place::model()->findAllByAttributes(array('id' => $id));

		// organise place object into an array
		$rows = array();
		foreach($places as $place) {
			$rows[] = array(
				'id' => $place->id,
				'type' => $place->type,
				'name' => $place->name,
				'detail' => $place->detail,
				'pic' => $place->pic,
				'comments' => $this->getComments($place->id)	// retrieve this place's comments
			);
		}

		// render view a place page with a place info
		$this->render('view',array('places'=>$places));
	}

	// retrieve a place's comments
	public function getComments($place_id) {	// require a place id
		// retrieve the comment of this place
		$comments = Comment::model()->findAllByAttributes(array('place_id' => $place_id));

		// organise the comments into an array
		$data = array();
		foreach($comments as $comment) {
			$data[] = array(
				'id' => $comment->id,
				'place_id' => $comment->place_id,
				'comment_text' => $comment->comment_text
			);
		}
		return $data;
	}
}