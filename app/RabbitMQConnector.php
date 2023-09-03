<?php

namespace App;

use Illuminate\Queue\Connectors\ConnectorInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConnector implements ConnectorInterface
{

	/**
	 * Establish a queue connection.
	 *
	 * @param  array $config
	 *
	 * @return \Illuminate\Contracts\Queue\Queue
	 */
	public function connect(array $config)
	{
		// create connection with AMQP
		$connection = new AMQPStreamConnection($config['host'], $config['port'], $config['login'], $config['password'],
				$config['vhost']);

		return new RabbitMQQueue(
			$connection,
			$config
		);
	}

}
