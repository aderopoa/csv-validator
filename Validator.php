<?php

class Validator
{

    protected $_error = [];
    protected $_has_error = false;

    protected function alphanum($string)
    {
        return ctype_alnum($string);
    }

    /**
     * Validates whether a date is in the correct format
     *
     * @param $date
     * @return bool
     */
    protected function date($date)
    {
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
            return false;
        }

        list($year, $month, $day) = explode('-', $date);

        return checkdate($month, $day, $year);
    }

    /**
     * Proper email validation, since FILTER_VALIDATE_EMAIL passes email
     * addresses containing a forward slash, which are actually invalid!
     *
     * @param $email
     * @return bool
     */
    protected function email($email)
    {
        if (filter_var($email, FILTER_SANITIZE_EMAIL) == $email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        } else {
            return false;
        }
    }

    /**
     * @param $letter
     * @param $min
     * @return bool
     */
    protected function minCount($letter, $min)
    {
        return (strlen($letter) > $min);
    }

    /**
     * @param $letter
     * @param $min
     * @return bool
     */
    protected function maxCount($letter, $min)
    {
        return (strlen($letter) < $min);
    }

    /**
     * @param $number
     * @param $min
     * @return bool
     */
    protected function min($number, $min)
    {
        return ($number > $min);
    }

    /**
     * @param $number
     * @param $max
     * @return bool
     */
    protected function max($number, $max)
    {
        return ($number < $max);
    }

    /**
     * @param $number
     * @return bool
     */
    protected function isSequential($number)
    {
        for ($i = 0; $i < strlen(trim($number))-1; $i++) {
            if (abs((int)$number{$i} - (int)$number{$i+1}) != 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * Is same digits
     *
     * @param $number
     * @return bool
     */
    protected function isSameDigits($number)
    {
        $check = [0, 101, 202, 303, 404, 505, 606, 707, 808, 909];

        if ((int)$number % 11 == 0 && in_array($number/11, $check)) {
            return true;
        }

        return false;
    }

    /**
     * checks if password consists of only digits
     * @param $value
     * @return bool
     */
    protected function numeric($value)
    {
        return ctype_digit($value);
    }

    /**
     * Check if password is exactly a certain amount of characters
     * @param $value
     * @param $length
     * @return bool
     */
    protected function characterLimit($value, $length)
    {
        return (strlen(trim($value)) == $length) ? true : false;
    }

    /**
     * Validates that the passed through value is a valid timestamp
     *
     * @param $strTimestamp
     * @return bool
     */
    protected function isValidTimeStamp($strTimestamp) {
        return ((int) $strTimestamp === $strTimestamp)
        && ($strTimestamp <= PHP_INT_MAX)
        && ($strTimestamp >= ~PHP_INT_MAX);
    }

    /**
     * validates currency
     * @param $amount
     * @return bool
     */
    protected function isValidAmount($amount)
    {
        if (is_numeric($amount) && (int) $amount > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param $value
     * @param array $expected
     * @return bool
     */
    protected function contains($value, array $expected)
    {
        return in_array($value, $expected);
    }

    protected function username($value)
    {
        return preg_match('/^[a-zA-Z0-9.]{5,}$/', $value);
    }

    protected function name($value)
    {
        return preg_match("/^[a-zA-Z'-]/", $value);
    }


    public function validateInput($input, array $conditions)
    {
        $errors = [];
        $response = [
            'status' => 'success',
            'error_messages' => []
        ];

        foreach ($conditions as $key => $condition) {
            if (method_exists($this, $key)) {
                if (!$this->$key($input, $condition)) {
                    $errors[] = [$key => "$input failed validation for $key"];
                }
            } else {
                $errors[] = [$key => "$input failed validation. $key does not exist."];
            }
        }

        if (!empty($errors)) {
            $response = [
                'status' => 'error',
                'error_messages' => $errors
            ];
        }

        return $response;
    }
}