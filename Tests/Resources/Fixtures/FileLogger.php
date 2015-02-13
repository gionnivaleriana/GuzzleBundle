<?php

namespace Kopjra\GuzzleBundle\Tests\Resources\Fixtures;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Class FileLogger
 * @author Joy Lazari <joy.lazari@gmail.com>
 * @package Resources\Fixtures
 */
class FileLogger implements LoggerInterface {

	/**
	 * @api
	 *
	 * @deprecated since 2.2, to be removed in 3.0. Use warning() which is PSR-3 compatible.
	 *
	 * @param $message
	 * @param array $context
	 */
	public function warn( $message, array $context = array() ) {
	}

	/**
	 * System is unusable.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return null
	 */
	public function emergency( $message, array $context = array() ) {
	}

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return null
	 */
	public function alert( $message, array $context = array() ) {
	}

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return null
	 */
	public function critical( $message, array $context = array() ) {
	}

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return null
	 */
	public function error( $message, array $context = array() ) {
	}

	/**
	 * @api
	 *
	 * @deprecated since 2.2, to be removed in 3.0. Use emergency() which is PSR-3 compatible.
	 *
	 * @param $message
	 * @param array $context
	 */
	public function emerg( $message, array $context = array() ) {
	}

	/**
	 * @api
	 *
	 * @deprecated since 2.2, to be removed in 3.0. Use critical() which is PSR-3 compatible.
	 *
	 * @param $message
	 * @param array $context
	 */
	public function crit( $message, array $context = array() ) {
	}

	/**
	 * @api
	 *
	 * @deprecated since 2.2, to be removed in 3.0. Use error() which is PSR-3 compatible.
	 *
	 * @param $message
	 * @param array $context
	 */
	public function err( $message, array $context = array() ) {
	}

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return null
	 */
	public function warning( $message, array $context = array() ) {
	}

	/**
	 * Normal but significant events.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return null
	 */
	public function notice( $message, array $context = array() ) {
	}

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return null
	 */
	public function info( $message, array $context = array() ) {
	}

	/**
	 * Detailed debug information.
	 *
	 * @param string $message
	 * @param array $context
	 *
	 * @return null
	 */
	public function debug( $message, array $context = array() ) {
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 *
	 * @return null
	 */
	public function log( $level, $message, array $context = array() ) {
	}
}