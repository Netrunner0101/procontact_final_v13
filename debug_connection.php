<?php
echo "=== PostgreSQL Connection Debug ===\n\n";

// Test 1: Check if PDO PostgreSQL is available
echo "1. Checking PDO PostgreSQL support...\n";
if (extension_loaded('pdo_pgsql')) {
    echo "   ✅ PDO PostgreSQL extension is loaded\n";
} else {
    echo "   ❌ PDO PostgreSQL extension is NOT loaded\n";
    exit(1);
}

// Test 2: Try to connect to server without database
echo "\n2. Testing connection to PostgreSQL server...\n";
try {
    $dsn = "pgsql:host=tfe;port=5432";
    echo "   DSN: $dsn\n";
    $pdo = new PDO($dsn, 'root', 'root', [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "   ✅ Server connection successful!\n";
    
    // Test 3: List existing databases
    echo "\n3. Listing existing databases...\n";
    $stmt = $pdo->query("SELECT datname FROM pg_database WHERE datistemplate = false ORDER BY datname");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($databases as $db) {
        echo "   - $db\n";
    }
    
    // Test 4: Create database if it doesn't exist
    echo "\n4. Creating 'agenda_app' database if needed...\n";
    if (!in_array('agenda_app', $databases)) {
        $pdo->exec("CREATE DATABASE agenda_app");
        echo "   ✅ Database 'agenda_app' created!\n";
    } else {
        echo "   ✅ Database 'agenda_app' already exists\n";
    }
    
} catch (PDOException $e) {
    echo "   ❌ Server connection failed: " . $e->getMessage() . "\n";
    echo "   Error Code: " . $e->getCode() . "\n";
    exit(1);
}

// Test 5: Connect to the specific database
echo "\n5. Testing connection to 'agenda_app' database...\n";
try {
    $dsn = "pgsql:host=tfe;port=5432;dbname=agenda_app";
    $pdo = new PDO($dsn, 'root', 'root', [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "   ✅ Database connection successful!\n";
    
    // Test 6: Check tables
    echo "\n6. Checking existing tables...\n";
    $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "   📋 No tables found - database is empty\n";
    } else {
        echo "   📋 Found " . count($tables) . " tables:\n";
        foreach ($tables as $table) {
            echo "     - $table\n";
        }
    }
    
} catch (PDOException $e) {
    echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "   Error Code: " . $e->getCode() . "\n";
}

echo "\n=== Debug Complete ===\n";
?>
