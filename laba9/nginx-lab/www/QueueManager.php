<?php
// www/QueueManager.php
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueManager {
    private $channel;
    private $queueName = 'lab7_queue';

    public function __construct() {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $this->channel = $connection->channel();
        $this->channel->queue_declare($this->queueName, false, true, false, false);
    }

    public function publish($data) {
        $msg = new AMQPMessage(json_encode($data), ['delivery_mode' => 2]);
        $this->channel->basic_publish($msg, '', $this->queueName);
    }

    public function consume(callable $callback) {
        $this->channel->basic_consume($this->queueName, '', false, true, false, false, function($msg) use ($callback) {
            $data = json_decode($msg->body, true);
            $callback($data);
        });

        while($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }
}