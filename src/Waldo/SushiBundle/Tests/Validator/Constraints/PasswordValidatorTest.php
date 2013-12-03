<?php

namespace Waldo\SushiBundle\Tests\Validator\Constraints;

use Waldo\SushiBundle\Validator\Constraints\Password;
use Waldo\SushiBundle\Validator\Constraints\PasswordValidator;

class PasswordValidatorTest extends \PHPUnit_Framework_TestCase
{

    protected $context;
    protected $validator;

    protected function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);
        $this->validator = new PasswordValidator();
        $this->validator->initialize($this->context);
    }

    protected function tearDown()
    {
        $this->context = null;
        $this->validator = null;
    }

    /**
     * @dataProvider getValidPasswords
     */
    public function testValidEmails($password)
    {
        $this->context->expects($this->never())
                ->method('addViolation');

        $this->validator->validate($password, new Password());
    }

    public function getValidPasswords()
    {
        return array(
            array('AaAa1.1.'),
            array('AaAa11!#$%&()+,-./:;<=>?@[\]^_{|}~'),
        );
    }

    /**
     * @dataProvider getInvalidPasswords
     */
    public function testNotValidEmails($password)
    {
        $this->context->expects($this->exactly(2))
                ->method('addViolation');

        $this->validator->validate($password, new Password());
    }

    public function getInvalidPasswords()
    {
        return array(
            array('aaaa11..'),
            array('aaaaAA..'),
            array('aaaaAA11'),
            
        );
    }

}
