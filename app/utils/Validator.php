<?php

namespace App\utils;

use DateTime;

class Validator
{
	// validate required
	static function isRequired($value)
	{
		return !empty($value);
	}
	// validate email
	static function isEmail($value)
	{
		if (!isset($value)) {
			return false;
		}
		return preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $value);
	}
	// validate phone number
	static function isPhoneNumber($value)
	{
		if (!isset($value)) {
			return false;
		}
		return preg_match('/((09|03|07|08|05|01)+([0-9]{8})\b)/', $value);
	}
	// validate date
	static function isDate($value)
	{
		if (!isset($value)) {
			return false;
		}
		$curentDate = new DateTime();
		$date = new DateTime($value);
		return $date >= $curentDate;
	}
	// validate password
	static function isPassword($value)
	{
		if (!isset($value)) {
			return false;
		}
		return strlen($value) >= 6;
	}
	// validate string
	static function isString($value)
	{
		if (!isset($value)) {
			return false;
		}
		return is_string($value);
	}
	// validate number
	static function isNumber($value)
	{
		if (!isset($value)) {
			return false;
		}
		return is_numeric($value);
	}
	// validate max length
	static function isMaxLength($value, $maxLength)
	{
		if (!isset($value)) {
			return false;
		}
		return strlen($value) <= $maxLength;
	}
	// validate min length
	static function isMinLength($value, $minLength)
	{
		if (!isset($value)) {
			return false;
		}
		return strlen($value) >= $minLength;
	}
	// validate score from 0 -> 10
	static function isScore($value)
	{
		if (!isset($value)) {
			return false;
		}
		return $value >= 0 && $value <= 10;
	}

	static function isBeforeToday($value)
	{
		if (!isset($value)) {
			return false;
		}
		$curentDate = new DateTime();
		$date = new DateTime($value);
		return $date < $curentDate;
	}

	static function validate(array $data, array $rules): array|bool
	{
		$errors = [];
		foreach ($rules as $key => $rule) {
			foreach ($rule as $r => $message) {
				if ($r === 'isRequired' && !self::isRequired($data[$key])) {
					$errors[$key] = $message;
				}
				if ($r === 'isEmail' && !self::isEmail($data[$key])) {
					$errors[$key] = $message;
				}
				if ($r === 'isPhoneNumber' && !self::isPhoneNumber($data[$key])) {
					$errors[$key] = $message;
				}
				if ($r === 'isDate' && !self::isDate($data[$key])) {
					$errors[$key] = $message;
				}
				if ($r === 'isPassword' && !self::isPassword($data[$key])) {
					$errors[$key] = $message;
				}
				if ($r === 'isString' && !self::isString($data[$key])) {
					$errors[$key] = $message;
				}
				if ($r === 'isNumber' && !self::isNumber($data[$key])) {
					$errors[$key] = $message;
				}
				if (str_starts_with($r, 'maxLength')) {
					$maxLength = (int)explode(':', $r)[1];
					if (!self::isMaxLength($data[$key], $maxLength)) {
						$errors[$key] = $message;
					}
				}
				if (str_starts_with($r, 'minLength')) {
					$minLength = (int)explode(':', $r)[1];
					if (!self::isMinLength($data[$key], $minLength)) {
						$errors[$key] = $message;
					}
				}
				if ($r === 'isScore' && !self::isScore($data[$key])) {
					$errors[$key] = $message;
				}

				if ($r === 'isBeforeToday' && !self::isBeforeToday($data[$key])) {
					$errors[$key] = $message;
				}
			}
		}
		return count($errors) > 0 ? $errors : false;
	}
}
