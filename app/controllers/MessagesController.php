<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use app\Forms\MessageForm;


class MessagesController extends ControllerBase
{
    /**
     * Index and Searches for messages
     */
    public function indexAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Messages', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
			$this->persistent->parameters = null;

            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "created DESC";

        $messages = Messages::find($parameters);
        if (count($messages) == 0) {
            $this->flash->notice("The search did not find any messages");

            $this->dispatcher->forward(array(
                "controller" => "messages",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $messages,
            'limit'=> 10,
            'page' => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
		$this->view->form = new MessageForm(new Messages());
    }

    /**
     * Edits a message
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $message = Messages::findFirstByid($id);
            if (!$message) {
                $this->flash->error("message was not found");

                $this->dispatcher->forward(array(
                    'controller' => "messages",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->id = $message->id;

            $this->tag->setDefault("id", $message->id);
            $this->tag->setDefault("name", $message->name);
            $this->tag->setDefault("phone", $message->phone);
            $this->tag->setDefault("email", $message->email);
            $this->tag->setDefault("message", $message->message);
            $this->tag->setDefault("created", $message->created);

			$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
			$this->view->form = new MessageForm(new Messages(), array(
				'edit' => true
			));

        }
    }

    /**
     * Creates a new message
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "messages",
                'action' => 'index'
            ));

            return;
        }

        $message = new Messages();
        $message->name = $this->request->getPost("name");
        $message->phone = $this->request->getPost("phone");
        $message->email = $this->request->getPost("email", "email");
        $message->message = $this->request->getPost("message");
        $message->created = $this->request->getPost("created");


        if (!$message->save()) {
            foreach ($message->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "messages",
                'action' => 'new'
            ));

            return;
        }

        //$this->flash->success("message was created successfully");

		//message was created successfully
        return 'true';
    }

    /**
     * Saves a message edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "messages",
                'action' => 'index'
            ));

            return;
        }

        $id = $this->request->getPost("id");
        $message = Messages::findFirstByid($id);

        if (!$message) {
            $this->flash->error("message does not exist " . $id);

            $this->dispatcher->forward(array(
                'controller' => "messages",
                'action' => 'index'
            ));

            return;
        }

        $message->name = $this->request->getPost("name");
        $message->phone = $this->request->getPost("phone");
        $message->email = $this->request->getPost("email", "email");
        $message->message = $this->request->getPost("message");
		$this->view->id = $message->id;

        if (!$message->save()) {
			$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
			$this->view->form = new MessageForm(new Messages(), array(
				'edit' => true
			));


            foreach ($message->getMessages() as $msg) {
                $this->flash->error($msg);
            }

            $this->dispatcher->forward(array(
                'controller' => "messages",
                'action' => 'edit',
                'params' => array($message->id)
            ));

            return;
        }

        return 'true';
    }

    /**
     * Deletes a message
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $message = Messages::findFirstByid($id);
        if (!$message) {
            $this->flash->error("message was not found");

            $this->dispatcher->forward(array(
                'controller' => "messages",
                'action' => 'index'
            ));

            return;
        }

        if (!$message->delete()) {

            foreach ($message->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "messages",
                'action' => 'index'
            ));

            return;
        }

        return 'true';
    }

}
