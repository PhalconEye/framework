<?php
/**
 * PhalconEye
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to phalconeye@gmail.com so we can send you a copy immediately.
 *
 */

namespace Engine\Form\Validator;

class Regex extends \Phalcon\Validation\Validator\Regex implements \Phalcon\Validation\ValidatorInterface
{

    public function __construct($params = array()){
        if (isset($params['message'])){
            $params['message'] = \Phalcon\DI::getDefault()->get('trans')->_($params['message']);
        }

        parent::__construct($params);
    }

}
