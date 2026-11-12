<?php

require_once __DIR__ . '/../src/Core/Database.php';
require_once __DIR__ . '/../src/Services/AiNlpEngine.php';
require_once __DIR__ . '/../src/Services/ScamScoringEngine.php';
require_once __DIR__ . '/../src/Services/BillOcrEngine.php';
require_once __DIR__ . '/../src/Services/SchemeMatcherEngine.php';
require_once __DIR__ . '/Unit/AiServicesTest.php';

use CivicMind\Tests\Unit\AiServicesTest;

AiServicesTest::runAll();
