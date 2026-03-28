<?php
require_once __DIR__ . '/../configuration/mongo.php';

class AdminLog
{
    private $manager;
    private $dbName;
    private $collection = 'admin_logs';

    public function __construct()
    {
        global $mongoManager;
        $this->manager = $mongoManager;
        $this->dbName = MONGO_DB;
    }

    public function logAction($adminId, $action, $details = [])
    {
        if (!$this->manager)
            return false;

        $bulk = new MongoDB\Driver\BulkWrite;
        $document = [
            'admin_id' => $adminId,
            'action' => $action,
            'details' => $details,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'timestamp' => new MongoDB\BSON\UTCDateTime()
        ];

        $bulk->insert($document);

        try {
            $this->manager->executeBulkWrite("$this->dbName.$this->collection", $bulk);
            return true;
        }
        catch (Exception $e) {
            error_log("Failed to insert log: " . $e->getMessage());
            return false;
        }
    }

    public function getRecentLogs($limit = 50)
    {
        if (!$this->manager)
            return [];

        $query = new MongoDB\Driver\Query([], [
            'sort' => ['timestamp' => -1],
            'limit' => $limit
        ]);

        try {
            $cursor = $this->manager->executeQuery("$this->dbName.$this->collection", $query);
            $results = [];
            foreach ($cursor as $document) {
                // Convert BSON DateTime to string for JSON serialization
                if (isset($document->timestamp)) {
                    $datetime = $document->timestamp->toDateTime();
                    $datetime->setTimezone(new DateTimeZone('Europe/Paris'));
                    $document->timestamp_formatted = $datetime->format('Y-m-d H:i:s');
                }
                $results[] = (array)$document;
            }
            return $results;
        }
        catch (Exception $e) {
            error_log("Failed to fetch logs: " . $e->getMessage());
            return [];
        }
    }
}
?>
