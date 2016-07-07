<?php
namespace App\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Email;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Regex;

class MessageForm extends Form
{
	public function initialize(\Messages $message = null, $options = null)
    {
		$this->setEntity($message);

        if (isset($options['edit']) && $options['edit']) {
			$this->add(new Hidden('id'));
        }

		$name=new Text("name");
		$name->addValidators(array(
            new StringLength(
				array(
					'messageMinimum' => 'Имя должно содержать минимум 3 символа',
					'min' => 3,
				)
			)
        ));

		$this->add($name);


		$phone=new Text("phone");
		$phone->addValidators(array(
            new Regex(
				array(
					'message'    => 'Неправильный формат телефона',
					'pattern'    => '/\+380[0-9]{9}+/',
					'allowEmpty' => true
				)
			)
        ));

		$this->add($phone);


		$email=new Email("email");
		$email->addValidators(array(
            new PresenceOf(
				array(
					'message' => 'Необходим Email'
				)
			),
			new EmailValidator(
					array(
						'message' => 'Email указан не верно'
					)
			)
        ));

		$this->add($email);


		$message=new TextArea("message");
		$message->addValidators(array(
            new PresenceOf(
				array(
					'message' => 'Сообщение должно быть заполнено'
				)
			),
			new StringLength(
				array(
					'messageMaximum' => 'Сообщение должно быть не больше 200 символов',
					'max' => 200,
				)
			)
        ));

		$this->add($message);
    }
}