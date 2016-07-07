<?php

namespace App\Library;

use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Http\Request;
use Phalcon\Mvc\Model\Criteria;

class MessagesGrid extends \Phalcon\Di\Injectable{
	private $limit=10;
	private $numberPage=1;
	public	$messages;
	private	$request;
	private	$parameters;
	private	$class;

	public function __construct($class) {
		$this->request = new Request();
		$this->class=$class;
	}

	public function getParameters(){
		if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, $this->class, $_POST);
            $this->parameters = $query->getParams();
        } else {
			$this->parameters = null;
        }

        if (!is_array($this->parameters)) {
            $this->parameters = array();
        }


		$this->parameters["order"] = $this->getSort();

		return $this->parameters;
	}

	private function getNumberPage(){
		if (!$this->request->isPost() && isset($_GET['page'])) {
			$this->numberPage = filter_var($_GET['page'], FILTER_VALIDATE_INT, array('options' => array('default' => 1)));
		}
		return $this->numberPage;
	}

	public function getPaginator(){
		$paginator = new Paginator(array(
            'data' => $this->messages,
            'limit'=> $this->limit,
            'page' => $this->getNumberPage()
        ));

		return $paginator->getPaginate();
	}

	public function setLimit($number){
		$this->limit=$number;
	}

	public function getSortableFields(){
		return array(
			'created.desc'=>'По дате добавления (desc)',
			'created.asc'=>'По дате добавления (asc)',
			'name.asc'=>'По имени (asc)',
			'name.desc'=>'По имени (desc)',
			'email.asc'=>'По email (asc)',
			'email.desc'=>'По email (desc)',
			'phone.asc'=>'По телефону (asc)',
			'phone.desc'=>'По телефону (desc)',
		);
	}

	private function getSort(){
		$order="created DESC";
		if(isset($_POST['sort']) && array_key_exists($_POST['sort'], $this->getSortableFields())){
			$sort=explode('.', $_POST['sort']);
			$order=$sort[0].' '.$sort[1];
		}

		return $order;
	}
}