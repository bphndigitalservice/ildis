<?php
namespace common\tests\unit\components;

use Codeception\Test\Unit;
use common\components\VisitorCounter;
use common\models\VisitorLog;

class VisitorCounterTest extends Unit
{
    public function testGenerateFingerprint()
    {
        $counter = new VisitorCounter();
        $fingerprint = $counter->generateFingerprint('192.168.1.1', 'Mozilla/5.0');

        $this->assertEquals(32, strlen($fingerprint));
        $this->assertEquals($fingerprint, $counter->generateFingerprint('192.168.1.1', 'Mozilla/5.0'));
        $this->assertNotEquals($fingerprint, $counter->generateFingerprint('192.168.1.2', 'Mozilla/5.0'));
    }

    public function testIsUniqueVisit()
    {
        $counter = new VisitorCounter();
        $fingerprint = $counter->generateFingerprint('10.0.0.1', 'TestAgent/1.0');
        $documentId = 'peraturan_123';

        $this->assertTrue($counter->isUniqueVisit($fingerprint, $documentId));

        $log = new VisitorLog();
        $log->visitor_fingerprint = $fingerprint;
        $log->visitor_cookie_id = 'test-cookie-id';
        $log->document_id = $documentId;
        $log->page_url = 'http://localhost/peraturan/view?id=123';
        $log->visit_date = date('Y-m-d');
        $log->visit_time = date('Y-m-d H:i:s');
        $log->is_unique = 1;
        $log->save();

        $this->assertFalse($counter->isUniqueVisit($fingerprint, $documentId));
    }

    public function testIsFirstVisitInRangeAcrossDays()
    {
        $counter = new VisitorCounter();
        $fingerprint = $counter->generateFingerprint('10.0.0.2', 'TestAgent/2.0');
        $documentId = null;
        $weekStart = date('Y-m-d', strtotime('monday this week'));

        $this->assertTrue($counter->isFirstVisitInRange($fingerprint, $documentId, $weekStart));

        $log = new VisitorLog();
        $log->visitor_fingerprint = $fingerprint;
        $log->visitor_cookie_id = 'test-cookie-week';
        $log->document_id = $documentId;
        $log->page_url = 'http://localhost/';
        $log->visit_date = $weekStart;
        $log->visit_time = $weekStart . ' 10:00:00';
        $log->is_unique = 1;
        $log->save();

        $this->assertFalse($counter->isFirstVisitInRange($fingerprint, $documentId, $weekStart));
        // Still unique for "today" if weekStart is not today
        if ($weekStart !== date('Y-m-d')) {
            $this->assertTrue($counter->isUniqueVisit($fingerprint, $documentId));
        }
    }
}
