<?php

namespace App;

use DateTime;
use Illuminate\Contracts\Queue\Queue as QueueContract;
use Illuminate\Queue\Queue;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPRuntimeException;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Str;

class RabbitMQQueue extends Queue implements QueueContract
{

    protected $connection;
    protected $channel;

    protected $declareExchange;
    protected $declareBindQueue;

    protected $defaultQueue;
    protected $configQueue;
    protected $configExchange;

    /**
     * @param AMQPStreamConnection $amqpConnection
     * @param array $config
     */
    public function __construct(AMQPStreamConnection $amqpConnection, $config)
    {
        $this->connection = $amqpConnection;
        $this->defaultQueue = $config['queue'];
        $this->configQueue = $config['queue_params'];
        $this->configExchange = $config['exchange_params'];
        $this->declareExchange = $config['exchange_declare'];
        $this->declareBindQueue = $config['queue_declare_bind'];

        $this->channel = $this->getChannel();
    }

    /**
     *
     * @param  string $job
     * @param  mixed $data
     * @param  string $queue
     *
     * @return bool
     */
    public function push($job, $data = '', $queue = null)
    {
        return $this->pushRaw($this->createPayload($job, $queue, $data), $queue);
    }

    /**
     *
     * @param  string $payload
     * @param  string $queue
     * @param  array $options
     *
     * @return mixed
     */
    public function pushRaw($payload, $queue = null, array $options = [])
    {
        $queue = $this->getQueueName($queue);

        $this->declareQueue($queue);

        if (isset($options['delay']) && $options['delay'] > 0) {
            list($queue, $exchange) = $this->declareDelayedQueue($queue, $options['delay']);
        } else {
            list($queue, $exchange) = $this->declareQueue($queue);
        }

        $message = new AMQPMessage($payload, [
            'Content-Type' => 'application/json',
            'delivery_mode' => 2,
        ]);

        $this->channel->basic_publish($message, $exchange, $queue);

        return true;
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTime|int $delay
     * @param  string $job
     * @param  mixed $data
     * @param  string $queue
     *
     * @return mixed
     */
    public function later($delay, $job, $data = '', $queue = null, $attempts = 1)
    {
        return $this->pushRaw($this->createPayload($job, $data, $attempts), $queue, ['delay' => $delay]);
    }

    /**
     *
     * @param string|null $queue
     *
     * @return \Illuminate\Queue\Jobs\Job|null
     */
    public function pop($queue = null)
    {
        $queue = $this->getQueueName($queue);

        try {
            $this->declareQueue($queue);
        } catch (AMQPRuntimeException $e) {
            $this->connection->reconnect();
            $this->declareQueue($queue);
        }

        $message = $this->channel->basic_get($queue);

        if ($message instanceof AMQPMessage) {
            return new RabbitMQJob($this->container, $this, $this->channel, $queue, $message);
        }

        return null;
    }

    /**
     * @param string $queue
     *
     * @return string
     */
    private function getQueueName($queue)
    {
        return $queue ?: $this->defaultQueue;
    }

    /**
     * @return AMQPChannel
     */
    private function getChannel()
    {
        return $this->connection->channel();
    }

    /**
     * @param $name
     * @return array
     */
    private function declareQueue($name)
    {
        $name = $this->getQueueName($name);
        $exchange = $this->configExchange['name'] ?: $name;

        if ($this->declareExchange) {

            $this->channel->exchange_declare(
                $exchange,
                $this->configExchange['type'],
                $this->configExchange['passive'],
                $this->configExchange['durable'],
                $this->configExchange['auto_delete']
            );
        }

        if ($this->declareBindQueue) {
            $this->channel->queue_declare(
                $name,
                $this->configQueue['passive'],
                $this->configQueue['durable'],
                $this->configQueue['exclusive'],
                $this->configQueue['auto_delete']
            );

            $this->channel->queue_bind($name, $exchange, $name);
        }

        return [$name, $exchange];
    }

    /**
     * @param string $destination
     * @param DateTime|int $delay
     *
     * @return array
     */
    private function declareDelayedQueue($destination, $delay)
    {
        $destination = $this->getQueueName($destination);
        $destinationExchange = $this->configExchange['name'] ?: $destination;
        $name = $this->getQueueName($destination) . '_deferred_' . $delay;
        $exchange = $this->configExchange['name'] ?: $destination;

        // declare exchange
        $this->channel->exchange_declare(
            $exchange,
            $this->configExchange['type'],
            $this->configExchange['passive'],
            $this->configExchange['durable'],
            $this->configExchange['auto_delete']
        );

        // declare queue
        $this->channel->queue_declare(
            $name,
            $this->configQueue['passive'],
            $this->configQueue['durable'],
            $this->configQueue['exclusive'],
            $this->configQueue['auto_delete'],
            false,
            new AMQPTable([
                'x-dead-letter-exchange' => $destinationExchange,
                'x-dead-letter-routing-key' => $destination,
                'x-message-ttl' => $delay * 1000,
            ])
        );

        $this->channel->queue_bind($name, $exchange, $name);

        return [$name, $exchange];
    }

    public function size($queue = null)
    {
        [, $size] = $this->getChannel()->queue_declare($queue, true);

        return $size;
    }

    /**
     * Get a random ID string.
     *
     * @return string
     */
    protected function getRandomId()
    {
        return Str::random(32);
    }

    protected function createPayloadArray($job, $queue, $data = ''): array
    {
        return array_merge(parent::createPayloadArray($job, $queue, $data), [
            'id' => $this->getRandomId(),
        ]);
    }
}
