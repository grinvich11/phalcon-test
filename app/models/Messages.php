<?php

use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phanbook\Validators\Phone;
use Phalcon\Validation\Validator\Regex;

class Messages extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $message;

	public function initialize()
    {
        $this->addBehavior(
            new Timestampable(
                array(
                    'beforeCreate' => array(
                        'field'  => 'created',
                        'format' => 'Y-m-d H:i:s'
                    )
                )
            )
        );
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();


		/*$validator->add(
			'name',
			new PresenceOf(
				array(
					'message' => 'The name is required'
				)
			)
		);*/

		$validator->add(
			'name',
			new StringLength(
				array(
					'messageMinimum' => 'Имя должно содержать минимум 3 символа',
					'min' => 3,
				)
			)
		);

		$validator->add(
			'email',
			new PresenceOf(
				array(
					'message' => 'Необходим Email'
				)
			)
		);

		/*$validator->add(
			'phone',
			new PresenceOf(
				array(
					'message' => 'Необходимо указать телефон'
				)
			)
		);*/

		/*$validator->add(
			'phone',
			new Phone(
				array(
					'message' => 'Неправильный формат телефона'
				)
			)
		);*/

		$validator->add('phone',
			new Regex(
				array(
					'message'    => 'Неправильный формат телефона',
					'pattern'    => '/\+380[0-9]{9}+/',
					'allowEmpty' => true
				)
			)
		);

        $validator->add(
            'email',
            new EmailValidator(
					array(
						'message' => 'Email указан не верно'
					)
			)
        );

		$validator->add(
			'message',
			new PresenceOf(
				array(
					'message' => 'Сообщение должно быть заполнено'
				)
			)
		);


		$validator->add(
			'message',
			new StringLength(
				array(
					'messageMaximum' => 'Сообщение должно быть не больше 200 символов',
					'max' => 200,
				)
			)
		);

        return $this->validate($validator);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'messages';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Messages[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Messages
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
