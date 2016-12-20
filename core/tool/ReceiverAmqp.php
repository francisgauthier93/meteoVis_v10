<?php

//namespace Acme\AmqpWrapper;

//use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ReceiverAmqp
{

    /**
     * Listens for incoming messages
     */
    public function listen()
    {
        $queue = $this->getQueueName();
        $exchange = 'xpublic';
        $exchange_type = 'topic';
        $exchange_key_meteocode = 'v00.dd.notify.meteocode.*.cmml.#'; // meteocode files
        $exchange_key_citypage = 'v00.dd.notify.citypage_weather.xml.*.#'; // citypage files

        $connection = new AMQPStreamConnection(
                'dd.weather.gc.ca', #host
                5672, #port
                'anonymous', #user
                'anonymous'          #password
        );

        $channel = $connection->channel();

        $channel->exchange_declare($exchange, $exchange_type, false, false,
                false);

        $channel->queue_declare(
                $queue, #queue name, the same as the sender
                false, #passive
                false, #durable
                false, #exclusive
                false           #autodelete
        );

        $channel->queue_bind($queue, $exchange, $exchange_key_meteocode);
        $channel->queue_bind($queue, $exchange, $exchange_key_citypage);

        while(!is_null($msg = $channel->basic_get($queue, false))
                && connection_status() == CONNECTION_NORMAL
                && Timer::getSeconds() < (Config::get('app.max_execution_time')*0.9))
        {
            $this->processOrder($msg);
            $channel->basic_ack($msg->delivery_info['delivery_tag']);
        }

//		Infinite loop
//        $channel->basic_consume(
//            $queue,                    		#queue
//            '',                             #consumer tag - Identifier for the consumer, valid within the current channel. just string
//            false,                          #no local - TRUE: the server will not send messages to the connection that published them
//            true,                           #no ack - send a proper acknowledgment from the worker, once we're done with a task
//            false,                          #exclusive - queues may only be accessed by the current connection
//            false,                          #no wait - TRUE: the server will not respond to the method. The client should not wait for a reply method
//            array($this, 'processOrder')    #callback - method that will receive the message
//            );
//
//        while(count($channel->callbacks)) {
//            $channel->wait();
//        }

        $this->saveQueueName($queue);
        $channel->close();
        $connection->close();
    }

    /**
     * @param $msg
     */
    public function processOrder($msg)
    {
//        echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";

        $sFileUrl = $this->extractUrlFromAmqpMessage($msg->body);
//        $iFileSize = $this->extractFileSizeFromAmqpMessage($msg->body);
//        $sDate = date('Y-m-d H:i:s');

        if(Filesystem::extension($sFileUrl) == Config::get('file.extension.xml'))
        {
            echo '<h1> ' . $sFileUrl . '</h1>';
            
            if(strpos($sFileUrl, 'meteocode'))
            {
                $oMeteoCodeXmlParser = new MeteoCodeXmlParser($sFileUrl);
                $aMeteoCodeList = $oMeteoCodeXmlParser->parse();

                $oMeteoCodeDao = new MeteoCodeDao(false);
                $oMeteoCodeDao->createMulti($aMeteoCodeList);
                $oMeteoCodeDao->deleteExpiredDataMulti($aMeteoCodeList);
                unset($oMeteoCodeDao);
            }
            else if(strpos($sFileUrl, 'citypage'))
            {
                $sNewFilePath = Config::get('path.real.root')
                        . Config::get('path.relative.root_to_citypage') 
                        . basename($sFileUrl);
                file_put_contents($sNewFilePath, file_get_contents($sFileUrl));
                chmod($sNewFilePath, 0644);
            }
        }
        
        // Save in CSV file
//        if(substr($sUrl, -3) == 'xml')
//        {
//            $sFileName = 'messages_xml.csv';
//        }
//        else if(substr($sUrl, -3) == 'csv')
//        {
//            $sFileName = 'messages_csv.csv';
//        }
//        else
//        {
//            $sFileName = 'messages_other.csv';
//        }
//
//        $fp = fopen('../../tmp/' . $sFileName, 'a');
//        fputcsv($fp, array($sDate, $iFileSize, $sUrl));
//        fclose($fp);
    }

//    private function extractFileSizeFromAmqpMessage($sAmqpMessage)
//    {
//        $aAmqpMessageInformation = explode(' ', $sAmqpMessage);
//
//        if(count($aAmqpMessageInformation) == 4)
//        {
//            return intval($aAmqpMessageInformation[1]);
//        }
//        else
//        {
//            return 0;
//        }
//    }
    
    private function extractUrlFromAmqpMessage($sAmqpMessage)
    {
        $aAmqpMessageInformation = explode(' ', $sAmqpMessage);

        if(count($aAmqpMessageInformation) == 4)
        {
            return trim($aAmqpMessageInformation[2] . $aAmqpMessageInformation[3]);
        }
        else
        {
            return null;
        }
    }

    private function getQueueName()
    {
        $sQueueName = '';
        if(file_exists(FILE_AMQP_QUEUE))
        {
            $sTmpQueueName = file_get_contents(FILE_AMQP_QUEUE);
            if(!empty($sTmpQueueName))
            {
                $sQueueName = $sTmpQueueName;
            }
        }
        else
        {
            $sQueueName = $this->generateQueueName();
        }

        return $sQueueName;
    }

    private function generateQueueName()
    {
        $sQueueName = 'cmc';

        $cstrong1 = false;
        $sQueueName .= '.' . bin2hex(openssl_random_pseudo_bytes(4, $cstrong1));

        $cstrong2 = false;
        $sQueueName .= '.' . bin2hex(openssl_random_pseudo_bytes(4, $cstrong2));

        return $sQueueName;
    }

    private function saveQueueName($sQueueName)
    {
        file_put_contents(FILE_AMQP_QUEUE, $sQueueName);
    }

}
