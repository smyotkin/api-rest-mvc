<?php
    namespace Core\Controller;

    use Valitron\Validator as v;

    class Controller
    {
        public function setError(int $code = 0, $msg = null)
        {
            $error = !empty(ERRORS[$code]) ? ERRORS[$code] : ERRORS[0];

            $response = [
                'error' => [
                    'code' => $code,
                    'msg'  => !empty($msg) ? $msg : $error
                ]
            ];

            \Flight::json($response, $code = 500, $encode = true, $charset = 'utf-8', $option = JSON_PRETTY_PRINT);

            exit();
        }

        public function validateParams($array, $availableParams, $default = null)
        {
            $v = new v($array);
            $v->mapFieldsRules($availableParams);
    
            $result = [
                'valid'        => true,
                'all_params'   => [], 
                'valid_params' => [],
            ];

            $valid = $v->validate();

            $errors = $v->errors();
            !empty($errors) ? $result['errors'] = $errors : null;

            foreach ($availableParams as $param => $rule) {
                if (!$valid && in_array('required', $rule) && isset($errors[$param]))
                    $result['valid'] = false;
                
                if (isset($array[$param]) && !isset($errors[$param])) {
                    $result['all_params'][$param] = $array[$param];
                } elseif (isset($default[$param])) {
                    $result['all_params'][$param] = $default[$param];
                    $result['notice'][$param][] = ucfirst($param) . " is invalid or missing, set to default ({$default[$param]})";
                } else {
                    $result['all_params'][$param] = null;
                }
            }

            $result['valid_params'] = array_filter($result['all_params'], 'strlen');

            return $result;
        }
    }