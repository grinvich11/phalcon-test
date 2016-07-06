<?php
namespace app\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Email;

class MessageForm extends Form
{
	public function initialize(\Messages $message = null, $options = null)
    {
        if (isset($options['edit']) && $options['edit']) {
			$this->add(new Hidden('id'));
        }

		$this->add(new Text("name"));
		$this->add(new Text("phone"));
		$this->add(new Email("email"));
		$this->add(new TextArea("message"));
    }
}