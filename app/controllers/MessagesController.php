<?php

use Phalcon\Mvc\View;
use App\Forms\MessageForm;
use App\Library\MessagesGrid;


class MessagesController extends ControllerBase
{
    /**
     * Index and Searches for messages
     */
    public function indexAction()
    {
		$grid=new MessagesGrid('Messages');

        $messages = Messages::find($grid->getParameters());
        if (count($messages) == 0) {
            $this->flash->notice("The search did not find any messages");

            return;
        }

		$grid->messages=$messages;
		$grid->setLimit(3);

        $this->view->grid = $grid;
		$this->view->page = $grid->getPaginator();
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
                    'action' => 'messages'
                ));

                return;
            }

            $this->view->id = $message->id;

			$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
			$this->view->form = new MessageForm($message, array(
				'edit' => true
			));
        }
    }

    /**
     * Creates a new message
     */
    public function createAction()
    {
		$error=false;
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "messages",
                'action' => 'index'
            ));

            return;
        }

		$form = new MessageForm();

		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
		$this->view->form = $form;

		if (!$form->isValid($_POST)) {
			$error=true;

			foreach ($form->getMessages() as $msg) {
				$this->flash->error($msg);
			}
		}else{
			$message = new Messages();

			$message->assign(array(
                'name' => $this->request->getPost('name'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
				'message' => $this->request->getPost('message'),
            ));

			if (!$message->save()) {
				$error=true;

				foreach ($message->getMessages() as $message) {
					$this->flash->error($message);
				}
			}
		}

		if($error){
			$this->dispatcher->forward(array(
				'controller' => "messages",
				'action' => 'new'
			));

			return;
		}

		//message was created successfully
        return 'true';
    }

    /**
     * Saves a message edited
     *
     */
    public function saveAction()
    {
		$error=false;
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
                'action' => 'messages'
            ));

            return;
        }

		$form = new MessageForm($message, array(
			'edit' => true
		));

		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
		$this->view->form = $form;

		if (!$form->isValid($_POST)) {
			$error=true;
			foreach ($form->getMessages() as $msg) {
				$this->flash->error($msg);
			}
		}else{
			$message->assign(array(
                'name' => $this->request->getPost('name'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
				'message' => $this->request->getPost('message'),
            ));

			if (!$message->save()) {
				$error=true;
				$this->view->id = $message->id;

				foreach ($message->getMessages() as $msg) {
					$this->flash->error($msg);
				}
			}
        }

		if($error){
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
                'action' => 'messages'
            ));

            return;
        }

        if (!$message->delete()) {
            foreach ($message->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "messages",
                'action' => 'messages'
            ));

            return;
        }

        return 'true';
    }

	public function messagesAction(){
		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
	}
}
