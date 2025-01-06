<?php

declare(strict_types=1);

/**
 * This script is used to check if all classes in the app directory are included in the class profiles.
 * It includes all PHP files in the app directory and generates class profiles based on the included classes.
 */

const ROOT_DIR = __DIR__ . '\\..\\..\\';

include_once 'helper.php';

includeAllFilesFrom(ROOT_DIR . 'app');
generateClassProfiles(__DIR__ . '\\..\\class-profiles.json');
