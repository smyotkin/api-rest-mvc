<?php
    namespace Core\Controller;

    use Core\Controller\Controller as Controller;
    use Core\Controller\Validator as Validate;
    
    use Core\Model\Database as DB;
    use Core\Model\CoreModel as CoreModel;

    class Api extends Controller
    {
        public function init()
        {
            \Flight::route('GET|POST /@token/@class/@method', function($token, $class, $method) {
                DB::connectEloquent();

                if ($this->checkToken($token)) {
                    $this->loadMethod($class, $method);
                }
            });

            \Flight::route('GET|POST /', function() {
                $this->setError(4);
            });

            \Flight::route('GET|POST *', function() {
                $this->setError(1);
            });

            \Flight::start();
        }

        public function loadMethod($className, $method)
        {
            $className = CONTROLLER_DIR . Validate::class($className);
            $method = Validate::method($method);

            if (class_exists($className) && method_exists($class = new $className(), $method)) {
                $class->$method();
            } else {
                $this->setError(3);
            }
        }

        public function checkToken($token) {
            $token = Validate::token($token);
            $result = CoreModel::where('token', $token)->first();
                                
            return !empty($result) ? $result : $this->setError(2);
        }
    }